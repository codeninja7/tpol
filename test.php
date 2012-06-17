<?
$test = '5';
$post = json_decode($_POST);
$repo = $post['repository'];
$repo_name = $repo['name'];

switch ($repo_name) {
    case 'nycup':
        exec('cd ~/public_html/git/nycup/');
        exec('git pull');
        break;
    case 'hyc':
        exec('cd ~/public_html/git/hyc/');
        exec('git pull');
        break;
    case 'tpol':
        exec('cd ~/public_html/git/tpol/');
        exec('git pull');
        break;
}