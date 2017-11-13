<?php
/**
 *
 * @version $Id: global.php,v 1.1 2007/05/10 16:47:46 leelight Exp $
 * @copyright leelight 2007
 */

$softName = "SUAS MapServer";

$softNamePrifix = "SUAS";

$softNamePostfix = "MapServer";

$softVersion = "3.21";

$softEdition = ".1213 beta1";

/**
* @description : If you publish your Mapserver online, please set it to 0, then SUAS will hide the hyperlink link to the path of install and setting,
* it will do good for security of your host.
* 0: false ; 1: ture
*/
$publishInstallAndSetting = 1;

/**
* @description : because of the security problem, I suggest that you can rename the folders' name of install and setting
* and edit the name here after having renamed the two folders.
*/
$installName = "install";
$settingName = "setting";

/**
 * @description :When you input very large data into database, please set the value a big number
 * It avoid abort the data processing when the execution time is out, unit is second.
 * But you need run PHP not in safe mode, otherwise this will not work!
 * Please open php.ini and set safe_mode to off.
 * safe_mode = Off
 */
$maxTimeOutLimit = 1800;//seconds

/**
 *
 * @description :The maximum upload fize size, Don not change the value, unless you know what you are doing!
 * You can change it, but it is mainly controled by your php setting, in most case it is 2048 kb(2 Mbyte)
 * You could change it more than 2048, but you still have such limitation until you change the value in PHP.ini
 *
 * The php.ini file contains all the configuration settings for your installation.
 * Sometimes these setting might be overridden by directives in apache .htaccess files or even with in the scripts
 * themselves but for the moment let's just concentrate on the ini file.
 * upload_max_filesize and post_max_size
 *
 */
$maxUploadFileSize = 20480; //kbytes

/**
 * @description :Set the static cache date time out, unit is second
 * 86400 is 24 hours one day
 * 3600 is one hour
 * 1800 is half an hour
 */
$cacheLimitTime = 86400; //seconds

/**
 * @description :Show the CopyRight at the left bottom of the image
 * Of course we hope you could set it to 1 to show our copyright. Thank you!
 * 0: hide copyright; 1: show copyright
 */
$showCopyright = 0;

/**
 * @description :Enable this, SVG map will use pixel(screen) coornidate instead of real coordinate
 * If the difference in your coordinate is too small, for example, (122.44220 37.70174) (122.44244 37.70247)
 * The difference between the two points is (0.00024, 0.00073), that is 1/300-400 of one pixel, and it is
 * difficult to be displayed in the screen, the stroke width is normally only 1 pixel!
 * Unless you want that the SVG map must contain the real coordinate information for other using, or you can set the
 * stroke-width to one value according to the difference in Style Defination(in this case, is 0.0001).
 * 0: use real coordinate; 1: use pixel coordinate
 */
$enableSVGPixelCoordinate = 0;

/*
* @description : SVG map could be outputted in two ways, one is DOM method, the other is stream method
* 0: using DOM method, need more memory, but easy to handle the exception
* 1: using Stream method, need less memory, but difficult to handle the exception
* If you feel that the SVG file is being created slowly, you could try stream method.
*/
define(enableStreamSVG, 1);

/**
 * @description :Record the log file for recording the errors and issues
 * 0: record log; 1: do not record log
 */
//$recordLog = 1;// if global is off, do not work
define(recordLog, 1);


/**
 * @description :log file's name
 * Please do not change it, unless you dont want others to check your log file
 * It uses relative path, root is SUAS
 */
//$recordFileName = "log.txt";// if global is off, do not work
define(recordFileName, "log.txt");


/**
 * @description :the directory name where daten can be uploaded to
 * It uses relative path, root is SUAS
 */
define(uploadDataDirectory, "data");


/**
 * @description :set the country code which SUAS will select it as output font for the text string
 * The default language is English
 * If your country are not listed below, please create a folder with short country name (it for Italy) in fonts folder
 * And find arial.ttf or the most oftenly used font in your system (for Windows, in c:/windows/Fonts/ directory), and rename the ttf file to arial.tff,
 * and copy the arial.ttf into the folder you have createn in the fonts folder.
 *
 * en English
 * cns Chinese Simple
 * cnt Chinese Traditional
 * de German
 * ru Russian
 */
define(outputTextCountry,"en");

/**
* OverlapRatio defines the ratio that the neighboring tiles could lay over
* this will avoid that the geometries between the 2 tiles could have gap or the text string was truncated by the boundary of tiles
* value is between 0 and 1, the bigger the value is, the gap can be reduced more, but notice, this will decelerate the rendering
* of map
*/
define("OverlapRatio", 0.1);

/**
* GetMap25DOverlapRatio is same as OverlapRatio, but only used for GetMap25D
*/
define("GetMap25DOverlapRatio", 0.5);

/*
* Standardized rendering pixel size is defined to be 0.28mm x 0.28mm (millimeters).
* Frequently, the true pixel size of the final rendering device is unknown in the web environment,
* and 0.28mm is a common actual size for contemporary video displays.
*
*/
define("PixelSzie", 0.00028);


/**
* Define your google Map key, please replace the string with your google map key for your webhost
* If you have not, please go to http://www.google.com/apis/maps/signup.html to apply for one
* This Key is used for http://localhost : "ABQIAAAAjpkAC9ePGem0lIq5XcMiuhT2yXp_ZAY8_ufC3CFXhHIE1NvwkxTS6gjckBmeABOGXIUiOiZObZESPg"
* For suasdemo easywms: "ABQIAAAAtJ5qpMrqIerkA8Mfc5qkhxTBSzLT7jrZTG_YfqpmmkL-hUGw7hQ9dKuYLqsW9Jjg3jyTb26_ol-YAw"
*/
define("GoogleMapKey", "ABQIAAAAjpkAC9ePGem0lIq5XcMiuhT2yXp_ZAY8_ufC3CFXhHIE1NvwkxTS6gjckBmeABOGXIUiOiZObZESPg");


/**
 *
 * @description : Select the database type
 *
 * 0 :MySQL
 * 1 :Postgresql
 * 2 :Oracle
 * 3 :MsSQL
 */
$databaseSelectFlag = 0 ;


switch ($databaseSelectFlag) {
    case 0: {
            require_once '../Models/MySQL.class.php';
        }
        break;
    case 1: {
            //require_once '../Models/Postgre.class.php';
        }
        break;
    case 2: {
            //require_once '../Models/Oracle.class.php';
        }
        break;
    case 3: {
            //require_once '../Models/MsSQL.class.php';
        }
        break;
}
?>