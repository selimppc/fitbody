<?php
class UploadForm extends CFormModel
{
        public $file;
        public $mime_type;
        public $size;
        public $name;
        public $filename;


        public function rules()
        {
                return array(
                        array('file', 'file', 'types'=>'gif, jpeg, jpg, png', 'maxSize' => 1024 * 1024 * 25, 'tooLarge' => 'The file was larger than 25MB. Please upload a smaller file.'),
                );
        }

        public function attributeLabels()
        {
                return array(
                        'file'=>'Upload files',
                );
        }

}
