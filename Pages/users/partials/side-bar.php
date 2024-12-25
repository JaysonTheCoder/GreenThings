<div class="side-bar">
    <div class="sidebar-toolbar">
        <h3>Green Things</h3>
    </div>
    <div class="sidebar-option">
        <ul>
            <li style="<?php 
                if($path === "/home") {
                    echo "background-color: #29c57c;";
                }
            
            ?>">
                <a href="/home">Home</a>
            </li>
            <li style="<?php 
                if($path === "/items-shared") {
                    echo "background-color: #29c57c;";
                }
            
            ?>">
                <a href="items-shared">Shared item</a>
            </li>
            <li style="<?php 
                if($path === "/requests") {
                    echo "background-color: #29c57c;";
                }
            
            ?>">
                <a href="/requests">Requests</a>
            </li>
            <li style="<?php 
                if($path === "/notification") {
                    echo "background-color: #29c57c;";
                }
            
            ?>">
                <a href="/notification">Notifications</a>
            </li>
            <li style="<?php 
                if($path === "/") {
                    echo "background-color: #29c57c;";
                }
            
            ?>">
                <a href="/logout">logout</a>
            </li>
            
        </ul>
    </div>
</div>