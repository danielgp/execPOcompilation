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

namespace danielgp\execPOcompilation;

/**
 * Description of compileLocalization
 *
 * @author Daniel Popiniuc
 */
class CompileLocalizationFiles
{

    use \danielgp\common_lib\CommonCode;

    private $applicationFlags;
    private $compilerExists;
    private $compiler;
    private $folderToSearchInto;
    private $filesFound;
    private $filesCompiled;
    private $foldersGiven = 0;

    public function __construct($givenFolder)
    {
        $this->applicationFlags = [
            'name'                => 'execPOcompilation',
            'available_languages' => [
                'en_US' => 'EN',
                'ro_RO' => 'RO',
            ]
        ];
        $this->handleLocalization();
        $this->checkCompilerExistance();
        echo $this->setHeaderHtml();
        if (is_array($givenFolder)) {
            foreach ($givenFolder as $currentFolder) {
                $this->foldersGiven++;
                $this->folderToSearchInto                 = filter_var($currentFolder, FILTER_SANITIZE_STRING);
                $this->filesFound[$this->foldersGiven]    = 0;
                $this->filesCompiled[$this->foldersGiven] = 0;
                echo $this->setHeaderFolder($currentFolder);
                $this->compileLocalizationFiles($currentFolder);
                echo $this->setFooterFolder($currentFolder);
            }
        } else {
            $this->foldersGiven++;
            $this->folderToSearchInto                 = filter_var($givenFolder, FILTER_SANITIZE_STRING);
            ;
            $this->filesFound[$this->foldersGiven]    = 0;
            $this->filesCompiled[$this->foldersGiven] = 0;
            echo $this->setHeaderFolder($givenFolder);
            $this->compileLocalizationFiles($givenFolder);
            echo $this->setFooterFolder($givenFolder);
        }
        echo $this->setFooterHtml();
    }

    private function checkCompilerExistance()
    {
        if (file_exists(GETTEXT_COMPILER)) {
            $this->compilerExists = true;
            $this->compiler       = filter_var(GETTEXT_COMPILER, FILTER_SANITIZE_STRING);
        } else {
            $this->compilerExists = false;
        }
    }

    private function compileLocalizationFiles($originDirectory)
    {
        if (!is_dir($originDirectory)) {
            echo _('i18n_Feedback_InvalidFolder');
            return '';
        }
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
                    $fileParts = pathinfo($inputFile);
                    if (isset($fileParts['extension']) && (strlen($fileParts['basename']) > 2)) {
                        if (strtolower($fileParts['extension']) === 'po') {
                            if ($this->filesFound[$this->foldersGiven] == 0) {
                                echo $this->setTableContent('Header', [
                                    '#',
                                    _('i18n_FeedbackTableHeader_FilePath'),
                                    _('i18n_FeedbackTableHeader_FileName'),
                                    _('i18n_FeedbackTableHeader_FileMoSize'),
                                    _('i18n_FeedbackTableHeader_CommandExecuted'),
                                    _('i18n_FeedbackTableHeader_CommandFeedback'),
                                    _('i18n_FeedbackTableHeader_FilePoSize'),
                                ]);
                            }
                            $this->filesFound[$this->foldersGiven] ++;
                            $outputFile = str_replace('.po', '.mo', $inputFile);
                            if ($this->compilerExists) {
                                $cmdToExecute         = $this->compiler . ' ' . $inputFile
                                    . ' --output-file=' . $outputFile
                                    . ' --statistics --check --verbose';
                                $out                  = null;
                                $feedbackFromCompiler = [];
                                exec($cmdToExecute . ' 2>&1', $out);
                                $this->filesCompiled[$this->foldersGiven] ++;
                                if (is_array($out)) {
                                    if (count($out) > 0) {
                                        foreach ($out as $key => $value) {
                                            $feedbackFromCompiler[] = $key . ' ===> ' . $value;
                                        }
                                    }
                                }
                                echo $this->setTableContent('Cell', [
                                    $this->filesFound[$this->foldersGiven],
                                    str_replace($this->folderToSearchInto, '', $fileParts['dirname']),
                                    $fileParts['basename'],
                                    filesize($inputFile),
                                    filter_var($cmdToExecute, FILTER_SANITIZE_STRING),
                                    filter_var(implode('<br/>', $feedbackFromCompiler), FILTER_SANITIZE_STRING),
                                    filesize($outputFile),
                                ]);
                            } else {
                                echo $this->setTableContent('Cell', [
                                    $this->filesFound[$this->foldersGiven],
                                    str_replace($this->folderToSearchInto, '', $fileParts['dirname']),
                                    $fileParts['basename'],
                                    filesize($inputFile),
                                    '<p style="color:red;">'
                                    . sprintf(_('i18n_CompilationNotPossible'), '<i>' . $this->compiler . '</i>')
                                    . '</p>',
                                    '---',
                                    '---',
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    private function handleLocalization()
    {
        $usedDomain = 'messages';
        if (isset($_GET['lang'])) {
            $_SESSION['lang'] = filter_var($_GET['lang'], FILTER_SANITIZE_STRING);
        } elseif (!isset($_SESSION['lang'])) {
            $_SESSION['lang'] = APPLICATION_DEFAULT_LANGUAGE;
        }
        /* to avoid potential language injections from other applications that do not applies here */
        if (!in_array($_SESSION['lang'], array_keys($this->applicationFlags['available_languages']))) {
            $_SESSION['lang'] = APPLICATION_DEFAULT_LANGUAGE;
        }
        T_setlocale(LC_MESSAGES, $_SESSION['lang']);
        if (function_exists('bindtextdomain')) {
            bindtextdomain($usedDomain, realpath('./locale'));
            bind_textdomain_codeset($usedDomain, 'UTF-8');
            textdomain($usedDomain);
        } else {
            echo 'No gettext extension/library is active in current PHP configuration!';
        }
    }

    private function setFooterFolder($currentFolder)
    {
        $sReturn = [];
        if (is_dir($currentFolder)) {
            if ($this->filesFound[$this->foldersGiven] > 0) {
                $sReturn[] = $this->setTableContent('Footer');
            }
            $aSprintF  = [
                '<i>' . htmlentities($this->folderToSearchInto) . '</i>',
                $this->filesFound[$this->foldersGiven],
                $this->filesCompiled[$this->foldersGiven]
            ];
            $sReturn[] = '<h4>'
                . sprintf(_('i18n_FinishedCompilation'), $aSprintF[0], $aSprintF[1], $aSprintF[2])
                . '</h4>';
        }
        $sReturn[] = '</div>';
        return implode('', $sReturn);
    }

    private function setFooterHtml()
    {
        $sReturn   = [];
        $aSprintF  = [
            '<i>' . _('i18n_Feedback_VariousFolders') . '</i>',
            array_sum($this->filesFound),
            array_sum($this->filesCompiled)
        ];
        $sReturn[] = '<h4>'
            . sprintf(_('i18n_FinishedCompilation'), $aSprintF[0], $aSprintF[1], $aSprintF[2])
            . '</h4>';
        $sReturn[] = '</div><!-- from main Tabber -->';
        return $this->setFooterCommon(implode('', $sReturn));
    }

    private function setHeaderHtml()
    {
        return $this->setHeaderCommon([
                'lang'       => str_replace('_', '-', $_SESSION['lang']),
                'title'      => $this->applicationFlags['name'],
                'css'        => 'css/main.css',
                'javascript' => 'js/tabber.min.js',
            ])
            . $this->setJavascriptContent('document.write(\'<style type="text/css">.tabber{display:none;}</style>\');')
            . '<h1>' . $this->applicationFlags['name'] . '</h1>'
            . $this->setHeaderLanguages()
            . '<div class="tabber" id="tab">';
    }

    private function setHeaderLanguages()
    {
        $sReturn = [];
        foreach ($this->applicationFlags['available_languages'] as $key => $value) {
            if ($_SESSION['lang'] === $key) {
                $sReturn[] = '<b>' . $value . '</b>';
            } else {
                $sReturn[] = '<a href="?lang=' . $key . '">' . $value . '</a>';
            }
        }
        return '<span class="language_box">'
            . implode(' | ', $sReturn)
            . '</span>';
    }

    private function setHeaderFolder($currentFolder)
    {
        $sReturn   = [];
        $sReturn[] = '<div class="tabbertab" title="' . $currentFolder . '">';
        if (is_dir($currentFolder)) {
            $sReturn[] = '<h4>'
                . sprintf(_('i18n_StartingCompilation'), '<i>' . htmlentities($this->folderToSearchInto) . '</i>')
                . '</h4>';
        }
        return implode('', $sReturn);
    }

    private function setTableContent($kind, $additionalContent = null)
    {
        $sReturn = [];
        switch ($kind) {
            case 'Cell':
                $sReturn[] = '<tr>';
                if (is_array($additionalContent)) {
                    $sReturn[] = '<td>' . implode('</td><td>', $additionalContent) . '</td>';
                }
                $sReturn[] = '</tr>';
                break;
            case 'Footer':
                $sReturn[] = '</tbody>'
                    . '</table>';
                break;
            case 'Header':
                $sReturn[] = '<table>'
                    . '<thead>'
                    . '<tr>';
                if (is_array($additionalContent)) {
                    $sReturn[] = '<th>' . implode('</th><th>', $additionalContent) . '</th>';
                }
                $sReturn[] = '</tr>'
                    . '</thead>'
                    . '<tbody>';
                break;
        }
        return implode('', $sReturn);
    }
}
