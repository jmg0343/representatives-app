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
                    $socialMedia = new SocialMedia;

                    $socialMedia->rep_name = $official['name'];
                    $socialMedia->name = 'Website';
                    $socialMedia->url = $url;

                    $socialMedia->save();
                }
            }

            foreach ($official['channels'] as $channel) {
                $socialMedia = new SocialMedia;

                $socialMedia->rep_name = $official['name'];
                $socialMedia->name = $channel['type'];
                $socialMedia->url = "https://" . strtolower($channel['type']) . ".com/" . $channel['id'];
                $socialMedia->handle = $channel['id'];

                $socialMedia->save();
            }
        }
    }
}