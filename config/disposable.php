<?php

return [
    // Determine which decision should be given if the rate limit is exceeded [allow / deny]
    'decision_rate_limit' => 'allow',

    // Determine which decision should be given if the domain has no MX DNS record [allow / deny]
    'decision_no_mx' => 'allow',

    // Makes use of the API key
    'key' => '',
];
