<?php
LoginManager::enforceLogin();
session_destroy();
header("Location: /");