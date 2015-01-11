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
define('APPLICATION_DEFAULT_LANGUAGE', 'en_US');
define('GETTEXT_COMPILER', 'D:\\www\\AppForDeveloper\\GetText\\msgfmt.exe');
define('LOCALIZATION_FOLDERS', implode('|', [
    realpath('../../') . '\\dpopiniuc.BitBucket\\daniel_projects',
    realpath('../../') . '\\dpopiniuc.BitBucket\\daniel_shared',
    realpath('../../') . '\\nis2mysql\\source',
    realpath('../../') . '\\salariu\\Salariu',
    realpath(__DIR__),
]));
