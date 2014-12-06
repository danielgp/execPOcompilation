<?php

/**
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Daniel Popiniuc
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

namespace NamespacePgdApp\Core;

/**
 * Description of compileLocalization
 *
 * @author Daniel Popiniuc
 */
class compileLocalization
{

    private $filesCompiled = 0;

    public function __construct($givenFolder)
    {
        // next line is just to generate an error log file that is for this module only and current date
        ini_set('error_log', ERROR_DIR . '/' . ERROR_FILE);
        $this->compileLocalizationFiles($givenFolder);
        echo '<hr/>I finished searching for [.po] files within [' . htmlentities($givenFolder) . '], resulting ' . $this->filesCompiled . ' files found and compiled!<hr/>';
    }

    private function compileLocalizationFiles($originDirectory)
    {
        clearstatcache();
        $givenDir = $originDirectory;
        $dir      = dir($givenDir);
        while ($file     = $dir->read()) {
            $inputFile = $givenDir . '\\' . $file;
            if (!in_array($file, ['.', '..'])) {
                if (is_dir($inputFile)) {
                    $this->compileLocalizationFiles($inputFile);
                } else {
                    clearstatcache();
                    $statistics = stat($inputFile);
                    $fileParts  = pathinfo($inputFile);
                    if (strlen($fileParts['basename']) > 2) {
                        if (strtolower($fileParts['extension']) === 'po') {
                            $cmdToExecute = GETTEXT_COMPILER . ' ' . $inputFile . ' --output-file=' . str_replace('.po', '.mo', $inputFile) . ' --statistics --check --verbose';
                            echo '<hr/>Executing::::: <b>' . $cmdToExecute . '</b>';
                            $out          = null;
                            exec($cmdToExecute . ' 2>&1', $out);
                            $this->filesCompiled++;
                            echo '<br/>';
                            var_dump($out);
                            if (is_array($out)) {
                                if (count($out) > 0) {
                                    foreach ($out as $key => $value) {
                                        echo '<br/>' . $key . ' ===> ' . $value;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
