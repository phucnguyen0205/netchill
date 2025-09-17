<?php

/**
 * Generates a consistent and unique avatar URL for a user.
 *
 * @param string|null $identifier A unique string for the user (e.g., email or username).
 * @return string The URL of the generated avatar.
 */
function generateUserAvatar($identifier)
{
    // Return a default placeholder if the identifier is empty.
    if (empty($identifier)) {
        return 'https://placehold.co/128x128?text=N/A';
    }

    // Hash the identifier to ensure a consistent, non-personal value.
    $hash = md5(strtolower(trim($identifier)));

    // Return the URL for a Gravatar image based on the hash.
    // The 'd=identicon' parameter creates a unique, generated image
    // if no Gravatar is associated with the email.
    return 'https://www.gravatar.com/avatar/' . $hash . '?d=identicon&s=150';
}