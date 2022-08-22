<?php

namespace App\Actions;

use App\Models\SocialMedia;

class AddSocialMedias
{
    public function execute($officials)
    {
        foreach ($officials as $official) {
            if (isset($official['urls'])) {
                foreach ($official['urls'] as $url) {
                    SocialMedia::firstOrCreate(
                        ['url' => $url],
                        [
                            'rep_name' => $official['name'],
                            'name' => 'website'
                        ]
                    );
                }
            }

            $socialMedias = $official['channels'] ?? [];

            foreach ($socialMedias as $channel) {
                SocialMedia::firstOrCreate(
                    ['url' => "https://" . strtolower($channel['type']) . ".com/" . $channel['id']],
                    [
                        'rep_name' => $official['name'],
                        'name' => $channel['type'],
                        'handle' => $channel['id']
                    ]
                );
            }
        }
    }
}