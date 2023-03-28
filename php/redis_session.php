<?php
function startRedisSession() {
    if (extension_loaded('redis')) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $redis = new Redis();
        try {
            $redis->connect('127.0.0.1', 6379);
            if ($redis->ping()) {
               // echo "Redis connection successful";
            } else {
                echo "Redis connection failed";
                die();
            }
        } catch (RedisException $e) {
            echo "Redis connection failed: " . $e->getMessage();
            die();
        }
        $sessionId = session_id();
        $sessionData = $redis->get($sessionId);

        if (!$sessionData) {
            $_SESSION = array();
        } else {
            $_SESSION = unserialize($sessionData);
        }

        register_shutdown_function('closeRedisSession');
    } else {
        echo "Redis is not installed.";
        die();
    }
}


function closeRedisSession() {
    if (extension_loaded('redis')) {
        $redis = new Redis();
        try {
            $redis->connect('127.0.0.1', 6379);
        } catch (RedisException $e) {
            echo "Redis connection failed: " . $e->getMessage();
            die();
        }
        global $sessionId;
        $redis->set($sessionId, serialize($_SESSION));
        session_write_close();
    } else {
        echo "Redis is not installed.";
        die();
    }
}

?>
