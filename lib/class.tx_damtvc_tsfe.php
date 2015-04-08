<?php
class tx_damtvc_tsfe extends tx_dam_tsfe
{
    /**
     * Fetch the files from the parent record data
     * 
     * @param string $content
     * @param string $conf
     * 
     * @deprecated Use FILES content object
     * 
     * @return type
     */
    public function fetchFileList($content, $conf)
    {
        $refField = trim($this->cObj->stdWrap($conf['refField'], $conf['refField.']));
        $refTable = trim($this->cObj->stdWrap($conf['refTable'], $conf['refTable.']));
        $random = trim($this->cObj->stdWrap($conf['random'], $conf['random.']));
        $slide = trim($this->cObj->stdWrap($conf['slide'], $conf['slide.']));
        $atIndex = trim($this->cObj->stdWrap($conf['atIndex'], $conf['atIndex.']));

        if (empty($refTable)) {
            $refTable = 'tt_content';
        }
        
        if (isset($this->cObj->parentRecord['data']['_ORIG_uid'])) {
            $uid = $this->cObj->parentRecord['data']['_ORIG_uid'];
        } else {
            $uid = $this->cObj->parentRecord['data']['uid'];
        }
        
        $files = $this->getAdditionalFiles($conf);
        foreach ($this->getReferencedFiles($refTable, $refField, $uid, $slide) as $file) {
            $files[] = $file->getUid();
        }

        $numFiles = count($files);
        if(!empty($atIndex)) {
            switch($atIndex) {
                case 'first':
                    $files = array($files[0]);
                    break;
                case 'last':
                    $files = array($files[$numFiles-1]);
                    break;
                default:
                    $files = array($files[intval($atIndex)]);
                    break;
            }
        }

        $numFiles = count($files);
        if ($numFiles > 1 && !empty($random)) {
            $randomPointer = rand(0, $numFiles - 1);
            $randomFile = array($files[$randomPointer]);
            $files = $randomFile;
        }
        
        return implode(',', $files);
    }
}