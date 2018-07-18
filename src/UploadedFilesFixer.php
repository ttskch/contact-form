<?php
namespace Ttskch\ContactForm;

class UploadedFilesFixer
{
    const TEMPNAM_PREFIX = 'ttskch-contact-form-uploaded-files';

    /**
     * enable to reuse uploaded files in other requests in the same session
     */
    public function fix()
    {
        foreach ($_FILES as $name => $input) {
            if (is_array($input['tmp_name'])) {
                foreach ($input['tmp_name'] as $i => $srcPath) {
                    $dstPath = tempnam(sys_get_temp_dir(), self::TEMPNAM_PREFIX);
                    move_uploaded_file($srcPath, $dstPath);
                    chmod($dstPath, 0644);
                    $_FILES[$name]['tmp_name'][$i] = $dstPath;
                }
            } else {
                $srcPath = $input['tmp_name'];
                $dstPath = tempnam(sys_get_temp_dir(), self::TEMPNAM_PREFIX);
                move_uploaded_file($srcPath, $dstPath);
                chmod($dstPath, 0644);
                $_FILES[$name]['tmp_name'] = $dstPath;
            }
        }
    }
}
