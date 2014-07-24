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
    protected $localPath;

    /**
     * @var string Git username to commit with
     */
    protected $username;

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
     * @var GithubClient Gtihub client
     */
    protected $githubClient;

    /**
     * @param string $localPath
     * @param string $username
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
        $localPath,
        $username,
        $token,
        $organization,
        $project,
        $originBranch = 'origin',
        $pullRequestTitle = '[AUTO] Updating translations from Crowdin',
        $pullRequestMessage = '',
        $branchPrefix = 'crowdin',
        $commitMessage = 'Updating translations from Crowdin'
    ) {
        if (!is_dir(sprintf('%s/.git', $localPath))) {
            throw new \Exception('Please configure your project in the directory %s first.', $this->localPath);
        }

        $this->localPath = $localPath;
        $this->branchPrefix = $branchPrefix;
        $this->commitMessage = $commitMessage;
        $this->username = $username;
        $this->token = $token;
        $this->organization = $organization;
        $this->project = $project;
        $this->originBranch = $originBranch;
        $this->pullRequestTitle = $pullRequestTitle;
        $this->pullRequestMessage = $pullRequestMessage;
        $this->githubClient = new GithubClient();
        $this->githubClient->authenticate($this->token, null, GithubClient::AUTH_HTTP_TOKEN);
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
            exec(sprintf($cmd, $this->localPath, $file));
        }
    }

    /**
     * Pull master branch of the project
     */
    public function pullMaster()
    {
        $cmd = 'cd %s && git checkout master && git pull origin master';
        exec(sprintf($cmd, $this->localPath));
    }

    /**
     * Create a local branch
     *
     * @param string $branch
     */
    public function createBranch($branch)
    {
        $cmd = 'cd %s && git checkout -b %s && git add .';
        exec(sprintf($cmd, $this->localPath, $branch));
    }

    /**
     * Delete a local branch
     *
     * @param string $branch
     */
    public function deleteBranch($branch)
    {
        $cmd = 'cd %s && git checkout master && git branch -D %s';
        exec(sprintf($cmd, $this->localPath, $branch));
    }

    /**
     * Commit changes
     *
     * @param string|null $message
     */
    public function commit($message = null)
    {
        $cmd = 'cd %s && git add . && git commit -m "%s"';
        exec(sprintf($cmd, $this->localPath, $message ?: $this->commitMessage));
    }

    /**
     * Push a branch to the origin
     *
     * @param string $branch
     */
    public function pushBranch($branch)
    {
        $cmd = 'cd %s && git push origin %s';
        exec(sprintf($cmd, $this->localPath, $branch));
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
} 
