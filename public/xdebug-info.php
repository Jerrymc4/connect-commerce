<?php
// No namespace issues here, just pure diagnostic info

// Basic PHP info
echo '<h1>Xdebug Diagnostic Information</h1>';

// Check if Xdebug is loaded
if (extension_loaded('xdebug')) {
    echo '<p style="color:green">✓ Xdebug extension is loaded</p>';
    echo '<p>Version: ' . phpversion('xdebug') . '</p>';
    
    // Check mode
    $mode = ini_get('xdebug.mode');
    echo '<p>Mode: ' . $mode . '</p>';
    
    if (strpos($mode, 'debug') !== false) {
        echo '<p style="color:green">✓ Debug mode is enabled</p>';
    } else {
        echo '<p style="color:red">✗ Debug mode is NOT enabled</p>';
    }
    
    // Check connection settings
    echo '<h2>Connection Settings</h2>';
    echo '<p>client_host: ' . ini_get('xdebug.client_host') . '</p>';
    echo '<p>client_port: ' . ini_get('xdebug.client_port') . '</p>';
    echo '<p>start_with_request: ' . ini_get('xdebug.start_with_request') . '</p>';
    echo '<p>idekey: ' . ini_get('xdebug.idekey') . '</p>';
    
    // Check trigger
    echo '<h2>Debug Session</h2>';
    if (isset($_COOKIE['XDEBUG_SESSION'])) {
        echo '<p style="color:green">✓ XDEBUG_SESSION cookie is set: ' . $_COOKIE['XDEBUG_SESSION'] . '</p>';
    } else {
        echo '<p style="color:red">✗ XDEBUG_SESSION cookie is NOT set</p>';
    }
    
    if (isset($_GET['XDEBUG_SESSION'])) {
        echo '<p style="color:green">✓ XDEBUG_SESSION GET parameter is set: ' . $_GET['XDEBUG_SESSION'] . '</p>';
    } else {
        echo '<p style="color:red">✗ XDEBUG_SESSION GET parameter is NOT set</p>';
    }
    
    // Test xdebug_break function
    echo '<h2>xdebug_break() Function</h2>';
    if (function_exists('xdebug_break')) {
        echo '<p style="color:green">✓ xdebug_break() function exists</p>';
    } else {
        echo '<p style="color:red">✗ xdebug_break() function does NOT exist</p>';
    }
    
    // Show loaded PHP modules
    echo '<h2>Loaded Extensions</h2>';
    echo '<pre>' . implode(', ', get_loaded_extensions()) . '</pre>';
    
} else {
    echo '<p style="color:red">✗ Xdebug extension is NOT loaded</p>';
}

// Add a simple breakpoint target
echo "<hr>Setting breakpoint target variable...<br>";
$breakpoint_target = true; // Set breakpoint here in IDE
echo "If debugger is attached, execution should pause before this line.<br>";

echo "<hr>Raw phpinfo() output:<br>";
phpinfo(); // Also output raw phpinfo for detailed comparison 