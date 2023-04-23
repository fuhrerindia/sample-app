<?php
include_once('./modules/backend.php');
if (DEV_MODE == true) {
    error_reporting(E_ALL);
    ini_set("display_errors", "1");
    http_response_code(200);
} else {
    ini_set("display_errors", "0");
}
$js_code_snippets = array();
$pathname = $_SERVER['REQUEST_URI'];
$pathname = explode("/", $pathname);
$pathname = $pathname[sizeof($pathname) - 1];
$pathname = "/$pathname";
function console_log($message)
{
    global $js_code_snippets;
    $message = str_replace('"', '\"', $message);
    array_push($js_code_snippets, "console.log(\"$message\");");
}
$yugal_styles_to_float = "";
function style($css)
{
    global $yugal_styles_to_float;
    $yugal_styles_to_float = "$yugal_styles_to_float\n$css";
}
function console_warn($message)
{
    global $js_code_snippets;
    $message = str_replace('"', '\"', $message);
    array_push($js_code_snippets, "console.warn(\"$message\");");
}
function console_error($message)
{
    global $js_code_snippets;
    $message = str_replace('"', '\"', $message);
    array_push($js_code_snippets, "console.error(\"$message\");");
}
$js_arrays = array();
$project_root = project_root();
function end_doc($arr = [])
{
    global $js_arrays, $js_code_snippets, $page, $yugal_head_called;
    $pr = project_root();
    if ($yugal_head_called == false) {
        head([]);
    }

    if (count($js_code_snippets) > 0) {
        $final_js_output = "";
        foreach ($page as $varname => $varvalue) {
            $varvalue = replace_backtick($varvalue);
            if (is_array($varvalue)) {
                $varvalue = php_array_to_js_object($varvalue);
            } else {
                $varvalue = "`$varvalue`";
            }
            $final_js_output = $final_js_output . <<<JS
            let $varname = $varvalue;
            JS;
        }
        foreach ($js_code_snippets as $code) {
            $final_js_output = $final_js_output . $code . "\n";
        }
        if (DEV_MODE == true) {
            file_put_contents("./modules/chunk.js", $final_js_output);
        }
        ?>
        <script src="<?php echo $pr . "/modules/chunk.js" ?>"></script>
        <?php
    }
    foreach ($arr as $each) {
        echo "<script src='" . $each . "'></script>";
    }
    foreach ($js_arrays as $each) {
        echo "<script src='" . $each . "'></script>";
    }
    echo "</body></html>";
    $js_arrays = array();
}
function project_root()
{
    $path = domain_root() . get_dir();
    return $path;
}
function script($code)
{
    global $js_code_snippets;
    array_push($js_code_snippets, $code);
}
function relative_to_absolute_path($path)
{
    /*
    THIS FUNCTION WILL DEFINE PATH ACCORDING TO string.php FILE
    */
    $project_root = project_root();
    $to_join = "";
    $path = trim($path, " ");
    if ($path[0] === "/") {
        $to_join = $path;
        return $project_root . $to_join;
    } elseif ($path[0] . $path[1] === "./") {
        $path = str_replace("./", "/", $path);
        $to_join = $path;
        return $project_root . $to_join;
    } elseif ($path[0] . $path[1] . $path[2] === "../") {
        console_log('STANDALONE APP: YUGAL BUILDS STANDALONE APP, PATH YOU ENTERED IS TRYING TO ACCESS FILES FROM OUTER DIRECTORY OF YUGAL');
        $p = str_replace("http://", "", $project_root);
        $p = str_replace("https://", "", $p);
        $p = explode("/", $p);
        array_pop($p);
        $a = "";
        foreach ($p as $each) {
            $a = $a . "/" . $each;
        }
        $path = str_replace("../", "/", $path);
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https:/' : 'http:/';
        return ($protocol . $a . $path);
    } else {
        return $path;
    }

}
function end_html()
{
    global $js_arrays;
    foreach ($js_arrays as $value) {
        echo "<script src=\"$value\"></script>";
    }
    echo "</body></html>";
}
$global_yugal_pages = [];
function replace_backtick($data)
{
    return str_replace("`", "\`", $data);
}

function makeyugalvalidheader($html)
{
    // Use a regular expression to match all HTML start tags
    $pattern = '/<([a-zA-Z]+)([^>]*)>/';
    $html = preg_replace_callback($pattern, function ($matches) {
        // Add the data-yugal attribute to the HTML start tag
        $tag = $matches[1];
        $attributes = $matches[2];
        $newAttributes = ' data-yugal' . $attributes;
        return "<$tag$newAttributes>";
    }, $html);
    return $html;
}

function def_comp($html)
{
    $html = str_replace("\n", "\\n", $html);
    $html = str_replace("\t", "\\t", $html);
    return $html;
}
function def_page($object)
{
    global $global_yugal_pages;
    $global_yugal_pages[$object['uri']] = $object;
    $object['render'] = def_comp(replace_backtick($object['render']));
    if (isset($object['css'])) {
        $object['css'] = str_replace("\t", "", str_replace("\n", "", replace_backtick($object['css'])));
    } else {
        $object['css'] = "";
    }
    if (!isset($object['header'])) {
        $object['header'] = "";
    } else {
        $object['header'] = replace_backtick(makeyugalvalidheader($object['header']));
    }
    if (!isset($object['willMount'])) {
        $object['willMount'] = "()=>{}";
    } else {
        $object['willMount'] = replace_backtick($object['willMount']);
    }
    if (!isset($object['didMount'])) {
        $object['didMount'] = "()=>{}";
    } else {
        $object['didMount'] = replace_backtick($object['didMount']);
    }
    if (!isset($object['didUnMount'])) {
        $object['didUnMount'] = "()=>{}";
    } else {
        $object['didUnMount'] = replace_backtick($object['didUnMount']);
    }
    if (!isset($object['willUnMount'])) {
        $object['willUnMount'] = "()=>{}";
    } else {
        $object['willUnMount'] = replace_backtick($object['willUnMount']);
    }
    $willm = replace_backtick($object['willMount']);
    $didm = replace_backtick($object['didMount']);
    $didu = replace_backtick($object['didUnMount']);
    $willu = replace_backtick($object['willUnMount']);
    script(<<<JS
        yugal.page({render: `{$object['render']}`,uri: `{$object['uri']}`,css: `{$object['css']}`,header: `{$object['header']}`,willMount: $willm,didMount: $didm,didUnMount: $didu,willUnMount: $willu});
    JS);
}

$yugal_http_error_page = ["render" => ""];
function def_error404($object)
{
    global $yugal_http_error_page;
    $yugal_http_error_page = $object;
    $object['render'] = def_comp(replace_backtick($object['render']));
    if (isset($object['css'])) {
        $object['css'] = str_replace("\t", "", str_replace("\n", "", replace_backtick($object['css'])));
    } else {
        $object['css'] = "";
    }
    if (!isset($object['header'])) {
        $object['header'] = "";
    } else {
        $object['header'] = replace_backtick($object['header']);
    }
    if (!isset($object['willMount'])) {
        $object['willMount'] = "()=>{}";
    } else {
        $object['willMount'] = replace_backtick($object['willMount']);
    }
    if (!isset($object['didMount'])) {
        $object['didMount'] = "()=>{}";
    } else {
        $object['didMount'] = replace_backtick($object['didMount']);
    }
    if (!isset($object['didUnMount'])) {
        $object['didUnMount'] = "()=>{}";
    } else {
        $object['didUnMount'] = replace_backtick($object['didUnMount']);
    }
    if (!isset($object['willUnMount'])) {
        $object['willUnMount'] = "()=>{}";
    } else {
        $object['willUnMount'] = replace_backtick($object['willUnMount']);
    }
    $willm = replace_backtick($object['willMount']);
    $didm = replace_backtick($object['didMount']);
    $didu = replace_backtick($object['didUnMount']);
    $willu = replace_backtick($object['willUnMount']);
    script(<<<JS
        yugal.error404({render: `{$object['render']}`,css: `{$object['css']}`,header: `{$object['header']}`,willMount: $willm,didMount: $didm,didUnMount: $didu,willUnMount: $willu});
    JS);
}
function compress_css_style($code)
{
    return str_replace(": ", ":", str_replace(["\n", "\t", "\r\n", "  "], "", $code));
}
function php_array_to_js_object($array)
{
    $out = "{";
    foreach ($array as $key => $value) {
        if (!is_int($key) || !is_bool($key)) {
            $key = "\"$key\"";
        }
        if (!is_integer($value) || !is_bool($value)) {
            $value = "`$value`";
        }
        $out = $out . $key . ":" . $value . ",";
    }
    $out = $out . "}";
    return $out;
}
$page = [];
function def_var($title, $val)
{
    global $page;
    $page[$title] = $val;
}
function export_screen($screen)
{
    echo $screen;
}
$js_arrays = array();
$yugal_head_called = false;
function head($attributes)
{
    global $pathname, $global_yugal_pages, $yugal_head_called, $yugal_styles_to_float, $yugal_http_error_page;
    $theme_c = THEME_COLOR;
    if (defined('SITE_TITLE')) {
        if (isset($attributes['title'])) {
            $site_title = $attributes['title'];
        } else {
            $site_title = SITE_TITLE;
        }
    } else {
        if (isset($attributes['title'])) {
            $site_title = $attributes['title'];
        } else {
            $site_title = "Unnamed Project - Yugal App";
        }
    }
    $project_root = project_root();
    script(<<<JS
            yugal.projectRoot = "{$project_root}";
        JS);
    $inc_path = $_SERVER['DOCUMENT_ROOT'] . get_dir();
    ?>
    <!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><?php
        if ($yugal_styles_to_float != "") {
            if (DEV_MODE == true) {
                $yugal_styles_to_float = compress_css_style($yugal_styles_to_float);
                file_put_contents("./modules/chunk.css", $yugal_styles_to_float);
            }
            echo "<link rel=\"stylesheet\" href=\"$project_root/modules/chunk.css\"/>";
        }
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="<?php echo $theme_c; ?>" />
        <link rel="stylesheet" href="<?php echo project_root(); ?>/modules/yugal.css"><?php
           if (defined('SPA_DISABLED')) {
               if (SPA_DISABLED == true) {
                   $value = str_replace('"', '\"', $site_title);
                   echo "<title>$site_title</title>";
                   echo "<meta name=\"title\" content=\"$site_title\">";
               }
           }
           if (defined('FAVICON')) {
               $fav_icon = FAVICON;
               $fav_icon = relative_to_absolute_path($fav_icon);
               echo "<link rel=\"icon\" type=\"image/x-icon\" href=\"$fav_icon\">";
           }
           foreach ($attributes as $key => $value) {
               if ($key === "css") {
                   foreach ($value as $css_file) {
                       $css_file = str_replace(".css", "", $css_file);
                       echo "<link rel='stylesheet' type='text/css' href=\"$project_root/design/$css_file.css\">";
                   }
               } elseif ($key === "meta") {
                   foreach ($value as $meta_name => $meta_value) {
                       echo "<meta name=\"$meta_name\" content=\"$meta_value\">";
                   }
               } elseif ($key === "custom") {
                   echo $value;
               } elseif ($key === "library") {
                   $__dir_name = get_dir();
                   foreach ($value as $lib_name) {
                       if (file_exists("$inc_path/lib/$lib_name/header.php")) {
                           include_once("$inc_path/lib/$lib_name/header.php");
                       }
                   }
               }
           }
           if (isset($global_yugal_pages[$pathname])) {
               if (isset($global_yugal_pages[$pathname]['header'])) {
                   echo makeyugalvalidheader($global_yugal_pages[$pathname]['header']);
               }
           } else {
            http_response_code(404);
               if (isset($yugal_http_error_page['header'])) {
                   echo makeyugalvalidheader($yugal_http_error_page['header']);
               }
           }
           ?>
    </head>

    <body>
        <?php
        echo "<noscript>" . GLOBAL_NOSCRIPT . "</noscript>";
        if (isset($attributes['library'])) {
            if (sizeof($attributes['library']) > 0) {
                foreach ($attributes['library'] as $lib) {
                    if (file_exists("$inc_path/lib/$lib_name/index.php")) {
                        include_once("$inc_path/lib/$lib_name/index.php");
                    }
                }
            }
        }
        if (isset($global_yugal_pages[$pathname])) {
            $present_page = $global_yugal_pages[$pathname];
        } else {
            $present_page = $yugal_http_error_page;
        }
        if (!isset($present_page['css'])) {
            $present_page['css'] = "";
        }
        if (!isset($present_page['willMount'])) {
            $present_page['willMount'] = "()=>{}";
        }
        if (!isset($present_page['didMount'])) {
            $present_page['didMount'] = "()=>{}";
        }
        if (!isset($present_page['willUnMount'])) {
            $present_page['willUnMount'] = "()=>{}";
        }
        if (!isset($present_page['didUnMount'])) {
            $present_page['didUnMount'] = "()=>{}";
        }
        $_willMount = $present_page['willMount'];
        $_didMount = $present_page['didMount'];
        $_willun = $present_page['willUnMount'];
        $_didun = $present_page['didUnMount'];
        script(<<<JS
        yugal.backend = true; 
        JS);
        $css_to_present = compress_css_style($present_page['css']);
        echo "<div id=\"yugal-root\">{$present_page['render']}</div><style id=\"yugal-page-specific-style\">{$css_to_present}</style><script src=\"{$project_root}/modules/yugal.js\"></script>";
        $yugal_head_called = true;

}
function add_js($src)
{
    global $js_arrays;
    $src = relative_to_absolute_path($src);
    array_push($js_arrays, $src);
}

?>