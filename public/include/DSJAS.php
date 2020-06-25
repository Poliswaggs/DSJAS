<?php

/*
Welcome to Dave-Smith Johnson & Son family bank!

This is a tool to assist with scam baiting, especially with scammers attempting to
obtain bank information or to attempt to scam you into giving money.

This tool is licensed under the MIT license (copy available here https://opensource.org/licenses/mit), so it
is free to use and change for all users. Scam bait as much as you want!

This project is heavily inspired by KitBoga (https://youtube.com/c/kitbogashow) and his LR. Jenkins bank.
I thought that was a very cool idea, so I created my own version. Now it's out there for everyone!

Please, waste these people's time as much as possible. It's fun and it does good for everyone.

*/

require_once ABSPATH . INC . "Module.php";
require_once ABSPATH . INC . "Module.php";

require_once ABSPATH . INC . "Customization.php";

/**
 * The main DSJAS load routine
 * 
 * Handles loading and sending modules, loading the theme and then sending that. In addition,
 * this routine handles setting the THEME_GLOBALS, which are used to send critical info
 * to the theme API/load functions.
 * 
 * @global array $GLOBALS["THEME_GLOBAL"]               Sends information to the theme and/or associated API or load functions
 * 
 * @param string   $fileName              (defaults to Index.php)      Used to load files from the theme content directory and the fileFilter engine
 * @param string   $dirName               (defaults to /)               The current single-level directory we should search for content in (ignored by fileFilter)
 * @param callable $moduleCallBack        (defaults to unset)  The callback we should jump to for the theme load hooks (used by things like the validator)
 * @param string   $defaultModuleHook     (defaults to all)   The name of the global hook we should call on theme load for modules that want content to load when the page does
 * @param array    $additionalModuleHooks (no defaults)    The names of additional callbacks which should be called on theme load (for example, user on user page load)
 * 
 * @return void This function should not return until the end of script execution
 */
function dsjas($fileName = "Index.php", $dirName = "/", $moduleCallBack = null, $defaultModuleHook = "all", $additionalModuleHooks = [])
{
    $fileFilterName = pathinfo($fileName, PATHINFO_BASENAME);
    $fileFilterName = strtolower(explode(".", $fileFilterName)[0]);

    $moduleManager = new ModuleManager($fileFilterName);
    $moduleManager->processModules($moduleCallBack);


    \gburtini\Hooks\Hooks::run("module_hook_event", [$defaultModuleHook, $moduleManager]);

    foreach ($additionalModuleHooks as $hook) {
        \gburtini\Hooks\Hooks::run("module_hook_event", [$hook, $moduleManager]);
    }


    $config = new Configuration(true, true, false, false);
    if ($config->getKey(ID_THEME_CONFIG, "config", "use_default")) {
        $useTheme = DEFAULT_THEME;
    } else {
        $useTheme = $config->getKey(ID_THEME_CONFIG, "extensions", "current_UI_extension");
    }

    // Define globals for theme API
    $GLOBALS["THEME_GLOBALS"] = [];

    $GLOBALS["THEME_GLOBALS"]["module_manager"] = $moduleManager;

    $theme = new Theme($fileName, $dirName, $useTheme);
    $theme->loadTheme();
    $theme->displayTheme();
}
