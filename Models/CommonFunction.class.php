<?php

/**
 *
 * @version $Id$
 * @copyright 2007
 */

/**
 *
 * @description :Limits the maximum execution time
 * @param  $maxTimeOutLimit from global.php
 */
function setConnectionTime ($maxTimeOutLimit)
{
    // Check for safe mode
    if (ini_get('safe_mode')) {
        // Do it the safe mode way
        // Do nothing
    } else {
        // Do it the regular way
        set_time_limit ($maxTimeOutLimit);
    }
}

function doConnectionTimeout()
{
    // if connection expired
    if (connection_timeout()) {
        print('<li class="error">Data Input</li>');
        print('              <li>Style Defination</li>
               <li>Create Metadata</li>
			</ul>
		<li class="first"><span>Setting</span></li>
			<ul class="second">
				<li>Database Settings</li>
			</ul>
		<li class="complete">Complete!</li>
		</ul>
	</td>
	<td id="content">');

        print('	<h1>Failure</h1>
			<p id="intro">The data processing meet connection timeout error. <br />
			Please set your maximum timeout value in php.ini file.<br /><br />
			<a href="javascript: history.go(-1)">Click here to go back</a>.</p>

				</td>
</tr>
</table>

</body>
</html>');
    }
    // if not connection expired
    if (!connection_timeout()) {
        print('<li class="error">Data Input</li>');
        print('              <li>Style Defination</li>
               <li>Create Metadata</li>
			</ul>
		<li class="first"><span>Setting</span></li>
			<ul class="second">
				<li>Database Settings</li>
			</ul>
		<li class="complete">Complete!</li>
		</ul>
	</td>
	<td id="content">');

        print('	<h1>Failure</h1>
			<p id="intro">You have aborted the data processing. <br />
			Maybe some data have been inputted into database, please check it.<br /><br />
			<a href="javascript: history.go(-1)">Click here to go back</a>.</p>

				</td>
</tr>
</table>

</body>
</html>');
    }
}
// if the user want to abort or back, if time timeout
function shutdownBeforeConnectionTimeout()
{
    register_shutdown_function('doConnectionTimeout');
}

function compareStringInsensitive($a, $b)
{
    if (strcasecmp($a, $b) == 0)
        return true;
    else
        return false;
}

/*
* Log text and timestamp in debug file
*/
function recordlog($logtxt)
{
    $root = "../";
    $recordFileName = $root . recordFileName;
    if (recordLog == 1) {
        if (recordFileName != "") {
            $file = fopen($recordFileName, "a");
            if ($file) {
                $tm = microtime_float();
                $tmf = sprintf("%.3f", $tm - floor($tm));
                fwrite($file, date('d/m/y H:i:s', $tm) . substr($tmf, 1) . " - " . $logtxt . "\r\n");
                fclose($file);
            } else {
                // if not exist, create log file
                createFile($recordFileName);
                $file = fopen($recordFileName, "a");
                $tm = microtime_float();
                $tmf = sprintf("%.3f", $tm - floor($tm));
                fwrite($file, date('d/m/y H:i:s', $tm) . substr($tmf, 1) . " - " . $logtxt . "\r\n");
                fclose($file);
            }
        }
    }
}

/*
*Get time in millisecs
 */
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function createFile($dir, $mode = "0777")
{
    if (! $dir) return 0;
    $dir = str_replace("\\", "/", $dir);

    $mdir = "";
    foreach(explode("/", $dir) as $val) {
        $mdir .= $val . "/";
        if ($val == ".." || $val == "." || trim($val) == "") continue;

        if (! file_exists($mdir)) {
            if (!@mkdir($mdir, $mode)) {
                $error = "Can not create log file, please create " . $recordFileName . " manully in SUAS root.";
                return false;
            }
        }
    }
    return true;
}

/*
   * Clear the log file
   * string $fileName filename or all the files
   * return true or false
   */
function clearLog($fileName)
{
    $temp = $fileName;
    if (file_exists($fileName)) {
        $b = @unlink($fileName);
        if($b){
            //createFile($fileName);//does not work, strange
            $FileHandle = @fopen($temp, 'w') ;
            @fclose($FileHandle);
            return true;
        }else return false;

    } else {
        $FileHandle = @fopen($temp, 'w') ;
        @fclose($FileHandle);
        return true;
    }
}

?>