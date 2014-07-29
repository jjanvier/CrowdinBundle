<?php

namespace Jjanvier\Bundle\CrowdinBundle\Synchronizer;

use Github\Api\PullRequest;
use Github\Client as GithubClient;

/**
 * Class GitHandler
 *
 * Executes all the Git commands required to synchronize the translations
 */
class GitHandler
{
    /**
     * @var string local path of the project
     */
    protected $projectPath;

    /**
     * @var string Git username to commit with
     */
    protected $username;

    /**
     * @var string Git email to commit with
     */
    protected $email;

    /**
     * @var string Git authentication URL token
     */
    protected $token;

    /**
     * @var string Git vendor to commit on
     */
    protected $organization;

    /**
     * @var string Git project to commit on
     */
    protected $project;

    /**
     * @var string name of the Git origin branch
     */
    protected $originBranch;

    /**
     * @var string prefix added to the local branch
     */
    protected $branchPrefix;

    /**
     * @var string commit message
     */
    protected $commitMessage;

    /**
     * @var string title of the Github pull request
     */
    protected $pullRequestTitle;

    /**
     * @var string message of the Github pull request
     */
    protected $pullRequestMessage;

    /**
     * @var GithubClient Github client
     */
    protected $githubClient;

    /**
     * @param string $username
     * @param string $email
     * @param string $token
     * @param string $organization
     * @param string $project
     * @param string $originBranch
     * @param string $pullRequestTitle
     * @param string $pullRequestMessage
     * @param string $branchPrefix
     * @param string $commitMessage
     *
     * @throws \Exception if the project path is not a Git repository
     */
    public function __construct(
        $username,
        $email,
        $token,
        $organization,
        $project,
        $originBranch = 'origin',
        $pullRequestTitle = '[AUTO] Updating translations from Crowdin',
        $pullRequestMessage = '',
        $branchPrefix = 'crowdin',
        $commitMessage = 'Updating translations from Crowdin'
    ) {
        $this->branchPrefix = $branchPrefix;
        $this->commitMessage = $commitMessage;
        $this->username = $username;
        $this->email = $email;
        $this->token = $token;
        $this->organization = $organization;
        $this->project = $project;
        $this->originBranch = $originBranch;
        $this->pullRequestTitle = $pullRequestTitle;
        $this->pullRequestMessage = $pullRequestMessage;

        $this->githubClient = new GithubClient();
        $this->authenticate();
    }

    /**
     * Cancel changes made on a file
     *
     * @param string $file
     */
    public function reset($file)
    {
        if (is_file($file)) {
            $cmd = 'cd %s && git checkout HEAD -- %s';
            $this->systemLog(sprintf($cmd, $this->projectPath, $file));
        }
    }

    /**
     * Create a local branch
     *
     * @param string $branch
     */
    public function createBranch($branch)
    {
        $cmd = 'cd %s && git checkout -b %s && git add .';
        $this->systemLog(sprintf($cmd, $this->projectPath, $branch));
    }

    /**
     * Delete a local branch
     *
     * @param string $branch
     */
    public function deleteBranch($branch)
    {
        $cmd = 'cd %s && git checkout master && git branch -D %s';
        $this->systemLog(sprintf($cmd, $this->projectPath, $branch));
    }

    /**
     * Commit changes
     *
     * @param string|null $message
     */
    public function commit($message = null)
    {
        $cmd = 'cd %s && git add . && git commit -m "%s"';
        $this->systemLog(sprintf($cmd, $this->projectPath, $message ?: $this->commitMessage));
    }

    /**
     * Push a branch to the origin
     *
     * @param string $branch
     */
    public function pushBranch($branch)
    {
        $cmd = 'cd %s && git push origin %s';
        $this->systemLog(sprintf($cmd, $this->projectPath, $branch));
    }

    /**
     * Get a local branch name
     *
     * @return string
     */
    public function getBranchName()
    {
        return sprintf('%s-%s', $this->branchPrefix, (new \DateTime())->format('Y-m-d-H-i'));
    }

    /**
     * Create a pull request on Github
     *
     * @param string $headBranch branch where your changes are implemented
     * @param string $baseBranch branch you want your changes pulled into
     */
    public function createPullRequest($headBranch, $baseBranch = 'master')
    {
        /** @var PullRequest $api */
        $api = $this->githubClient->api('pull_request');
        $api->create($this->organization, $this->project, array(
                'base'  => $baseBranch,
                'head'  => sprintf('%s:%s', $this->username, $headBranch),
                'title' => $this->pullRequestTitle,
                'body'  => $this->pullRequestMessage
            )
        );
    }

    /**
     * Authenticate the user on Github.
     */
    public function authenticate()
    {
        $this->githubClient->authenticate($this->token, null, GithubClient::AUTH_HTTP_TOKEN);
    }

    /**
     * Clone the Github project
     */
    public function cloneProject()
    {
        $url = sprintf('https://github.com/%s/%s.git', $this->organization, $this->project);

        $this->systemLog(sprintf('git clone %s %s', $url, $this->projectPath));
        $this->systemLog(sprintf('cd %s && git config user.name "%s"', $this->projectPath, $this->username));
        $this->systemLog(sprintf('cd %s && git config user.email %s', $this->projectPath, $this->email));
    }

    /**
     * @param string $path
     *
     * @return GitHandler
     */
    public function setProjectPath($path)
    {
        $this->projectPath = $path;

        return $this;
    }

    /**
     * Execute a command and log it.
     */
    protected function systemLog($command)
    {
        echo sprintf("\nExecuting command: %s... ", $command);
        system($command, $status);
        echo sprintf("\nDone with result %d.", $status);
    }
}
