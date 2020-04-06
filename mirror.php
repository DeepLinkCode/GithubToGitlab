<?php
/**
 * Github to Gitlab mirror
 *
 * Author: Deepender Choudhary
 *
 * License Mit https://choosealicense.com/licenses/mit
 */

ini_set('max_execution_time', INF);  //SET EXECUTION TIME HERE IT'S INIFINITE

date_default_timezone_set('Asia/Kolkata'); // SET Your Timezone

$__DIR = str_replace('\\', '/', realpath(__DIR__ .'/')); //SCRIPT PATH

define('BASEPATH', $__DIR);

//set here which repository to fetch
define('SOURCE_REPOSITORY', 'git@github.com:example/example.git');

//set here which repository to push
define('TARGET_REPOSITORY', 'git@gitlab.com:user/example.git');

// local git cache directory
define('LOCAL_CACHE', BASEPATH.'/git-mirror-cache/repo.git');

define('GIT_LOGS', BASEPATH.'/gitlogs');

if (!file_exists(LOCAL_CACHE)) {
    mkdir(LOCAL_CACHE);
}

if (!file_exists(GIT_LOGS)) {
    mkdir(GIT_LOGS);
}

chdir(LOCAL_CACHE);

$commands = array();

if (!is_dir(sprintf('%s/%s', LOCAL_CACHE, 'refs'))) {
    $commands[] = sprintf('git clone --mirror %s %s', SOURCE_REPOSITORY, LOCAL_CACHE);
} else {
    $commands[] = sprintf('git fetch -p origin');
}

$commands[] = sprintf('git push --mirror %s', TARGET_REPOSITORY);

$out = []; $i =1;
foreach ($commands as $command) {
    $out[$i] = "Executing $command ";
    $tmp = pipeExecute($command.' 2>&1'); 
    $i++;
    $out[$i] = $tmp;
    $i++;
}

$currentTime = date( 'd-m-Y--h-i-s-a', time () );

file_put_contents(BASEPATH.'/gitlogs/git-'.$currentTime.'.txt', var_export($out, true)."\n\n");  //git push mirror array logs

/**
 * Execute cmds in shell
 * @return array
 */
function pipeExecute($cmd, $input='') {
    $proc = proc_open($cmd, array(
        0 => array('pipe','r'),
        1 => array('pipe','w'),
        2 => array('pipe','w')
    ), $pipes);
    
    fwrite($pipes[0], $input);
    fclose($pipes[0]);
    
    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    
    $rtn = proc_close($proc);
    
    return array(
        'stdout'=>$stdout,
        'stderr'=>$stderr,
        'return'=>$rtn
    );
}