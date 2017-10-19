<?php
// Helper for prevent XSS in view when user input data display
function h(string $text)
{
    return htmlspecialchars($text);
}
