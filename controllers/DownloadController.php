<?php

namespace app\controllers;

use Yii;

class DownloadController extends \yii\web\Controller
{
    private $download_failure_text = "";
    private $renamed_destination_during_download = "";

    public function actionIndex()
    {
        
        $model = new \yii\base\DynamicModel(['DOWNLOADURL', 'NEWFILENAME']);
        $model->addRule(['DOWNLOADURL'], 'required');
        $model->addRule(['DOWNLOADURL'], 'string', ['max' => 1500]);
        $model->addRule(['NEWFILENAME'], 'string', ['max' => 500]);

        // frontend check of valid file types
        $model->addRule(['DOWNLOADURL'], function ($attribute, $params) {
            $check_ok = false;
            $file_parts = pathinfo($this->$attribute);

            // Sometimes, there is not filename in the url :-/
            if (array_key_exists('extension', $file_parts)) 
            {
                switch(strtoupper($file_parts['extension']))
                {
                    case strtoupper("jpg"):
                        $check_ok = true;
                        break;

                    case strtoupper("jpeg"):
                        $check_ok = true;
                        break;
                
                    case strtoupper("png"):
                        $check_ok = true;
                        break;

                    case strtoupper("svg"):
                        $check_ok = true;
                        break;
                
                    case "": // Handle file extension for files ending in '.'
                    case NULL: // Handle no file extension
                    break;
                }
            } else {
                $check_ok = true;
            }    
            if (!$check_ok)
            {
                $this->addError($attribute, 'Only jpg, jpeg, png or svg is allowed!');
            }
        });

        // Default path is param is not found (fallback)
        $path = ".";
        if (array_key_exists('downloadpath_logos', Yii::$app->params)) 
        {
            $downloadpath_logos = Yii::$app->params['downloadpath_logos'];
            $path = $downloadpath_logos;
        }

        if (Yii::$app->request->post())
		{
            if ($model->load(Yii::$app->request->post()))
            {
                if ($model->validate()) {
                    $url = $model->DOWNLOADURL;
                    $newfilename = $model->NEWFILENAME;
                    $destination = $this->filenamefinal($url, $path, $newfilename);
                    $realpath = $this->get_absolute_path($destination);
                    if ($this->download($url, $path, $newfilename))
                    {
                        Yii::$app->session->setFlash('success', "<pre>$url</pre>Download OK.<br>File saved to:<pre>$realpath</pre>" . ($this->renamed_destination_during_download !== "" ? ("<br>File renamed during download to:<pre>" . $this->renamed_destination_during_download . "</pre>") : ""));
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', "<pre>$url</pre>Download failed" . ($this->download_failure_text !== "" ? "<br>".$this->download_failure_text : ""));
                    }
                } else {
                    // validation failed: $errors is an array containing error messages
                    // No flash is needed...
                    $errors = $model->errors;
                }
            }
        }
        
        return $this->render('index',
            [
                 'model' => $model
            ]
        );
    }

    // create the full filename with path
    private function filenamefinal($url, $destinationpath, $newfilename)
    {
        // Use basename() function to return the base name of file
        $file_name = basename($url);
        
        $destination = $destinationpath  . $file_name;
        if ($newfilename !== "")
        {
            $destination = $destinationpath . $newfilename;
        }
        return $destination;
    }

    // Download the file from the given url
    // If the file does not match with the allowed filetype, the file won't be written to the disk.
    private function download($url, $destinationpath, $newfilename)
    {
        // extra info to the user, if something goes wrong
        $this->download_failure_text = "";

        $this->renamed_destination_during_download = "";

        $destination = $this->filenamefinal($url, $destinationpath, $newfilename);

        // Ok, also check, if what we do would like to download really is valud (just in case ;-))
        $allowed_file_types = ['image/png', 'image/jpeg', 'image/svg+xml', 'image/svg'];
        $content = file_get_contents($url);
        $fh = fopen('php://memory', 'w+b');
        fwrite($fh, $content);
        $mime_type = mime_content_type($fh);
        if (! in_array($mime_type, $allowed_file_types)) {
            $this->download_failure_text = "Illegal filetype [$mime_type] detected!";
            return false;
        }
        fclose($fh);

        $file_parts = pathinfo($url);
        // Sometimes, there is not filename in the url :-/
        //   E.g. http://darth.vader/saber_light/
        // In this case, we'll add the extension based on the mimetype
        if (!array_key_exists('extension', $file_parts)) 
        {
            $destination = $destination . str_replace('+xml', '', str_replace('image/', '.', $mime_type));
            $this->renamed_destination_during_download = $destination;
        }    

        // write the file
        if (file_put_contents($destination, file_get_contents($url)))
        {
            $this->download_failure_text = "Could not write the file!";
            return true;
        }
        else
        {
            return false;
        }
    }

    // alternative method for realpath (which does not work)
    private function get_absolute_path($path) {
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.' == $part) continue;
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $absolutes);
    }
}
