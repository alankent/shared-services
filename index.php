<?php
/**
 * Render shared services as simple HTML.
 */


// All modules, queries, etc indexed by fully qualified name.
$modules = array();
$services = array();
$structs = array();
$enums = array();
$extensions = array();
$queries = array();
$commands = array();
$events = array();


/**
 * Convert a type into HTML markup, linking to type names.
 * @param string $type The type to mark up, such as "string", "array[x.y.z]", "by_locale[string]".
 * @return string The HTML markup for the type
 */
function markupType($type)
{
    global $structs;
    global $enums;

    $i = strrpos($type, '[');
    if ($i !== false) {
        $j = strpos($type, ']', $i);
        $namedType = substr($type, $i + 1, $j - $i - 1);
        $prefix = substr($type, 0, $i + 1);
        $suffix = substr($type, $j);
    } else {
        $namedType = $type;
        $prefix = '';
        $suffix = '';
    }

    if (isset($enums[$namedType])) {
        $html = $prefix;
        $html .= "<a href='?enum=" . $namedType . "'>" . $namedType . "</a>";
        $html .= $suffix;
    } elseif (isset($structs[$namedType])) {
        $html = $prefix;
        $html .= "<a href='?struct=" . $namedType . "'>" . $namedType . "</a>";
        $html .= $suffix;
    } else {
        $html = $type;
    }

    return $html;
}

/**
 * Render the specified module.
 * @param string $name The module to render.
 */
function renderModule($moduleName)
{
    global $modules;
    $module = $modules[$moduleName];
    outputHeader("Module $moduleName");

    // TODO: XSD does not allow module description/summary?

    if (count($module->service) > 0) {
        echo "<h2>Services</h2>\n";
        echo "<ul>\n";
        foreach ($module->service as $service) {
            $serviceName = (string)$service['name'];
            echo "<li><a href='?service=$moduleName.$serviceName'>$serviceName</a>\n";
            echo "    <ul>\n";
            foreach ($service->command as $command) {
                $name = (string)$command['name'];
                echo "    <li>Command <a href='?command=$moduleName.$serviceName.$name'>$name</a>";
                echo "    <ul>\n";
                foreach ($command->arguments->argument as $argument) {
                    echo "    <li>" . (string)$argument['name'] . " - " . markupType((string)$argument->type);
                }
                echo "    </ul>\n";
            }
            foreach ($service->query as $query) {
                $name = (string)$query['name'];
                echo "    <li>Query <a href='?query=$moduleName.$serviceName.$name'>$name</a>";
                echo "    <ul>\n";
                foreach ($query->arguments->argument as $argument) {
                    echo "    <li>" . (string)$argument['name'] . " - " . markupType((string)$argument->type);
                }
                echo "    </ul>\n";
            }
            foreach ($service->event as $event) {
                $name = (string)$event['name'];
                echo "    <li>Event <a href='?event=$moduleName.$serviceName.$name'>$name</a>";
                echo "    <ul>\n";
                foreach ($event->arguments->argument as $argument) {
                    echo "    <li>" . (string)$argument['name'] . " - " . markupType((string)$argument->type);
                }
                echo "    </ul>\n";
            }
            echo "    </ul>\n";
        }
        echo "</ul>\n";
    }

    if (count($module->struct) > 0) {
        echo "<h2>Structs</h2>\n";
        echo "<ul>\n";
        foreach ($module->struct as $struct) {
            $structName = (string)$struct['name'];
            echo "<li><a href='?struct=$moduleName.$structName'>$structName</a>\n";
            echo "    <ul>\n";
            foreach ($struct->properties->property as $property) {
                echo "    <li>" . (string)$property['name'] . " - " . markupType((string)$property->type);
            }
            echo "    </ul>\n";
        }
        echo "</ul>\n";
    }

    if (count($module->enum) > 0) {
        echo "<h2>Enums</h2>\n";
        echo "<ul>\n";
        foreach ($module->enum as $enum) {
            $enumName = (string)$enum['name'];
            echo "<li><a href='?enum=$enumName'>$enumName</a>\n";
            echo "    <ul>\n";
            foreach ($enum->value as $value) {
                echo "    <li>$value</li>\n";
            }
            echo "    </ul>\n";
        }
        echo "</ul>\n";
    }

    if (count($module->extension) > 0) {
        echo "<h2>Extensions</h2>\n";
        echo "<ul>\n";
        foreach ($module->extension as $extension) {
            $extensionName = (string)$extension['name'];
            echo "<li><a href='?extension=$extensionName'>$extensionName</a></li>\n";
        }
        echo "</ul>\n";
    }

    outputFooter();
}

/**
 * Render the specified service.
 * @param string $name The serviceto render.
 */
function renderService($serviceName)
{
    global $services;
    $service = $services[$serviceName];
    outputHeader("Service $serviceName");
    $serviceName = (string)$service['name'];
    echo "<ul>\n";
    outputSummaryAndDescription($service);
    foreach ($service->command as $command) {
        $name = (string)$command['name'];
        echo "    <li>Command <a href='?command=$serviceName.$name'>$name</a>";
        echo "    <ul>\n";
        outputSummaryAndDescription($command);
        foreach ($command->arguments->argument as $argument) {
            echo "    <li>" . (string)$argument['name'] . " - " . markupType((string)$argument->type);
            echo "    <ul>\n";
            outputSummaryAndDescription($argument);
            echo "    </ul>\n";
        }
        echo "    </ul>\n";
    }
    foreach ($service->query as $query) {
        $name = (string)$query['name'];
        echo "    <li>Query <a href='?query=$serviceName.$name'>$name</a>";
        echo "    <ul>\n";
        outputSummaryAndDescription($query);
        foreach ($query->arguments->argument as $argument) {
            echo "    <li>" . (string)$argument['name'] . " - " . markupType((string)$argument->type);
            echo "    <ul>\n";
            outputSummaryAndDescription($argument);
            echo "    </ul>\n";
        }
        echo "    </ul>\n";
    }
    foreach ($service->event as $event) {
        $name = (string)$event['name'];
        echo "    <li>Event <a href='?event=$serviceName.$name'>$name</a>";
        echo "    <ul>\n";
        outputSummaryAndDescription($event);
        foreach ($event->arguments->argument as $argument) {
            echo "    <li>" . (string)$argument['name'] . " - " . markupType((string)$argument->type);
            echo "    <ul>\n";
            outputSummaryAndDescription($argument);
            echo "    </ul>\n";
        }
        echo "    </ul>\n";
    }
    echo "    </ul>\n";
    outputFooter();
}

/**
 * Render the specified struct.
 * @param string $name The struct to render.
 */
function renderStruct($structName)
{
    global $structs;
    outputHeader("Struct $structName");
    $struct = $structs[$structName];
    echo "<ul>\n";
    outputSummaryAndDescription($struct);
    foreach ($struct->properties->property as $property) {
        echo "    <li>" . (string)$property['name'] . " - " . markupType((string)$property->type);
        echo "    <ul>\n";
        outputSummaryAndDescription($property);
        echo "    </ul>\n";
    }
    echo "</ul>\n";
}

/**
 * Render the specified enum.
 * @param string $name The enum to render.
 */
function renderEnum($enumName)
{
    global $enums;
    outputHeader("Enum $enumName");
    $enum = $enums[$enumName];
    echo "<ul>\n";
    outputSummaryAndDescription($enum);
    foreach ($enum->value as $value) {
        echo "    <li>$value\n";
    }
    echo "</ul>\n";
}

/**
 * Render the specified extension.
 * @param string $name The extension to render.
 */
function renderExtension($name)
{
    // TODO
    global $extensions;
    echo "RENDER EXTENSION\n";
    var_dump($extensions[$name]);
}

/**
 * Render the specified query.
 * @param string $name The query to render.
 */
function renderQuery($name)
{
    // TODO
    global $queries;
    echo "RENDER QUERY\n";
    var_dump($queries[$name]);
}

/**
 * Render the specified command.
 * @param string $name The command to render.
 */
function renderCommand($name)
{
    // TODO
    global $commands;
    echo "RENDER COMMAND\n";
    var_dump($commands[$name]);
}

/**
 * Render the specified event.
 * @param string $name The event to render.
 */
function renderEvent($name)
{
    // TODO
    global $events;
    echo "RENDER EVENT\n";
    var_dump($events[$name]);
}




/**
 * Output HTML page header with title.
 */
function outputHeader($title)
{
    echo <<<EOF
<html>
<head>
<title>$title</title>
</head>
<body>
<h1>$title</h1>
EOF;
}

/**
 * Close off the HTML for the page.
 */
function outputFooter()
{
    echo "</html>\n";
}

/**
 * Output summary and description (if present).
 */
function outputSummaryAndDescription($node)
{
    if ($node->summary) {
        echo "<li>Summary: " . ((string)$node->summary) . "\n";
    }
    if ($node->description) {
        echo "<li>Description: " . ((string)$node->description) . "\n";
    }
    if ($node->extensible) {
        echo "<li>Extensible: " . ((string)$node->extensible) . "\n";
    }
    if ($node->required) {
        echo "<li>Required: " . ((string)$node->required) . "\n";
    }
}

/**
 * Render the home page
 */
function renderHomePage()
{
    global $services;
    global $modules;
    outputHeader("Magento Shared Services");

    echo "<h2>Modules</h2>\n";
    echo "<ul>\n";
    foreach ($modules as $moduleName => $module) {
        echo "<li><a href='?module=$moduleName'>$moduleName</a></li>\n";
    }
    echo "</ul>\n";

    echo "<h2>Services</h2>\n";
    echo "<ul>\n";
    foreach ($services as $serviceName => $service) {
        echo "<li><a href='?service=$serviceName'>$serviceName</a></li>\n";
    }
    echo "</ul>\n";

    outputFooter();
}


/**
 * Load one XML specification file, merging it into the set of known specification information.
 */
function loadXmlSpecification($file)
{
    global $modules;
    global $services;
    global $structs;
    global $enums;
    global $extensions;
    global $queries;
    global $commands;
    global $events;

    $xml = simplexml_load_file($file);

    foreach ($xml->module as $module) {
        $moduleName = (string)$module['name'];
        $modules[$moduleName] = $module;

        foreach ($module->service as $service) {
            $serviceName = $moduleName . '.' . $service['name'];
            $services[$serviceName] = $service;

            foreach ($service->query as $query) {
                $queryName = $serviceName . '.' . $query['name'];
                $queries[$queryName] = $query;
            }

            foreach ($service->event as $event) {
                $eventName = $serviceName . '.' . $event['name'];
                $events[$eventName] = $event;
            }

            foreach ($service->command as $command) {
                $commandName = $serviceName . '.' . $command['name'];
                $commands[$commandName] = $command;
            }
        }

        foreach ($module->struct as $struct) {
            $structName = $moduleName . '.' . $struct['name'];
            $structs[$structName] = $struct;
        }

        foreach ($module->enum as $enum) {
            $enumName = $moduleName . '.' . $enum['name'];
            $enums[$enumName] = $enum;
        }

        foreach ($module->extension as $extension) {
            $extensionName = $moduleName . '.' . $extension['name'];
            $extensions[$extensionName] = $extension;
        }
    }
}

/**
 * Load all the XML specification files in the directory tree.
 */
function loadXmlSpecifications()
{
    global $modules;
    global $services;
    global $structs;
    global $enums;
    global $extensions;
    global $queries;
    global $commands;
    global $events;

    // Find all the *.xml files.
    $folder = '.';
    $pattern = '#^\./[a-zA-Z].*\.xml$#';
    $dir = new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS);
    $ite = new RecursiveIteratorIterator($dir);
    $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
    foreach($files as $fileMatches) {
        $file = $fileMatches[0];
        loadXmlSpecification($file);
    }

    // Sort alphabetically so iterating by name lists more nicely.
    ksort($modules);
    ksort($services);
    ksort($structs);
    ksort($enums);
    ksort($extensions);
    ksort($queries);
    ksort($commands);
    ksort($events);
}


// Main program
loadXmlSpecifications();

$actions = [
    'module' => 'renderModule',
    'service' => 'renderService',
    'struct' => 'renderStruct',
    'enum' => 'renderEnum',
    'extension' => 'renderExtension',
    'query' => 'renderQuery',
    'command' => 'renderCommand',
    'event' => 'renderEvent',
];
$done = false;
foreach ($actions as $key => $action) {
    if (isset($_GET[$key])) {
        $action($_GET[$key]);
        $done = true;
        break;
    }
}
if (!$done) {
    renderHomePage();
}
