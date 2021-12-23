<style>
    table {
        background-color: #F3F3F3;
        border-collapse: collapse;
        width: 100%;
        margin: 15px 0;
    }

    th {
        background-color: #2c3e50;
        color: #FFF;
        cursor: pointer;
        padding: 5px 10px;
    }

    th small {
        font-size: 9px;
    }

    td,
    th {
        text-align: left;
    }

    a {
        text-decoration: none;
    }

    td a {
        color: #663300;
        display: block;
        padding: 5px 10px;
    }

    th a {
        padding-left: 0
    }

    td:first-of-type a {
        background: url(./assets/images/file.png) no-repeat 10px 50%;
        padding-left: 35px;
    }

    th:first-of-type {
        padding-left: 35px;
    }

    td:not(:first-of-type) a {
        background-image: none !important;
    }

    tr:nth-of-type(odd) {
        background-color: #E6E6E6;
    }

    tr:hover td {
        background-color: #CACACA;
    }

    tr:hover td a {
        color: #000;
    }

    /* icons for file types (icons by famfamfam) */

    /* images */
    table tr td:first-of-type a[href$=".jpg"],
    table tr td:first-of-type a[href$=".png"],
    table tr td:first-of-type a[href$=".gif"],
    table tr td:first-of-type a[href$=".svg"],
    table tr td:first-of-type a[href$=".jpeg"] {
        background-image: url(./assets/images/image.png);
    }

    /* zips */
    table tr td:first-of-type a[href$=".zip"] {
        background-image: url('<?= base_url('/assets/images/zip.png') ?>');
    }

    /* css */
    table tr td:first-of-type a[href$=".css"] {
        background-image: url(./assets/images/css.png);
    }

    /* docs */
    table tr td:first-of-type a[href$=".doc"],
    table tr td:first-of-type a[href$=".docx"],
    table tr td:first-of-type a[href$=".ppt"],
    table tr td:first-of-type a[href$=".pptx"],
    table tr td:first-of-type a[href$=".pps"],
    table tr td:first-of-type a[href$=".ppsx"],
    table tr td:first-of-type a[href$=".xls"],
    table tr td:first-of-type a[href$=".xlsx"] {
        background-image: url(./assets/images/office.png)
    }

    /* videos */
    table tr td:first-of-type a[href$=".avi"],
    table tr td:first-of-type a[href$=".wmv"],
    table tr td:first-of-type a[href$=".mp4"],
    table tr td:first-of-type a[href$=".mov"],
    table tr td:first-of-type a[href$=".m4a"] {
        background-image: url(./assets/images/video.png);
    }

    /* audio */
    table tr td:first-of-type a[href$=".mp3"],
    table tr td:first-of-type a[href$=".ogg"],
    table tr td:first-of-type a[href$=".aac"],
    table tr td:first-of-type a[href$=".wma"] {
        background-image: url(./assets/images/audio.png);
    }

    /* web pages */
    table tr td:first-of-type a[href$=".html"],
    table tr td:first-of-type a[href$=".htm"],
    table tr td:first-of-type a[href$=".xml"] {
        background-image: url(./assets/images/xml.png);
    }

    table tr td:first-of-type a[href$=".php"] {
        background-image: url(./assets/images/php.png);
    }

    table tr td:first-of-type a[href$=".js"] {
        background-image: url(./assets/images/script.png);
    }

    /* directories */
    table tr.dir td:first-of-type a {
        background-image: url(./assets/images/folder.png);
    }
</style>
<script src="<?= assets_url(); ?>js/sorttable.js"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div><!-- /.col -->
                <div class="col-sm-6">
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-1">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title" id="tile-form"> <?= $page_header; ?></h3>
                                <a class="btn btn-primary btn-sm icon-btn " href="javascript:void(0)" onclick="backup();"><i class="fa fa-database"></i> Backup Database</a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-1">
                            <table class="sortable">
                                <thead>
                                    <tr>
                                        <th>Filename</th>
                                        <th>Type</th>
                                        <th>Size <small>(bytes)</small></th>
                                        <th>Date Modified</th>
                                        <th width="1px">#</th>
                                        <th width="1px">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // error_reporting(0);
                                    // Opens directory
                                    $myDirectory = opendir("./backup/db");

                                    // Gets each entry
                                    while ($entryName = readdir($myDirectory)) {
                                        $dirArray[] = $entryName;
                                    }

                                    // Finds extensions of files
                                    function findexts($filename) {
                                        $filename = strtolower($filename);
                                        $exts = explode("[/\\.]", $filename);
                                        $n = count($exts) - 1;
                                        $exts = $exts[$n];
                                        return $exts;
                                    }

                                    // Closes directory
                                    closedir($myDirectory);

                                    // Counts elements in array
                                    $indexCount = count($dirArray);

                                    // Sorts files
                                    sort($dirArray);

                                    // Loops through the array of files
                                    for ($index = 0; $index < $indexCount; $index++) {
                                        // Allows ./?hidden to show hidden files
                                        if ($_SERVER['QUERY_STRING'] == "hidden") {
                                            $hide = "";
                                            $ahref = "./backup/db";
                                            $atext = "Hide";
                                        } else {
                                            $hide = ".";
                                            $ahref = "./?hidden";
                                            $atext = "Show";
                                        }

                                        if (substr($dirArray[$index], 0, 1) != $hide) {

                                            // Gets File Names
                                            $name = $dirArray[$index];
                                            $namehref = $dirArray[$index];

                                            // Gets Extensions 
                                            $extn = pathinfo($dirArray[$index], PATHINFO_EXTENSION);

                                            // Gets file size 

                                            $size = number_format(@filesize('./backup/db/' . $dirArray[$index]));

                                            // Gets Date Modified Data
                                            $modtime = date("M j Y g:i A", @filemtime('./backup/db/' . $dirArray[$index]));
                                            $timekey = date("YmdHis", @filemtime('./backup/db/' . $dirArray[$index]));
                                            // Prettifies File Types, add more to suit your needs.
                                            switch ($extn) {
                                                case "png":
                                                    $extn = "PNG Image";
                                                    break;
                                                case "jpg":
                                                    $extn = "JPEG Image";
                                                    break;
                                                case "svg":
                                                    $extn = "SVG Image";
                                                    break;
                                                case "gif":
                                                    $extn = "GIF Image";
                                                    break;
                                                case "ico":
                                                    $extn = "Windows Icon";
                                                    break;

                                                case "txt":
                                                    $extn = "Text File";
                                                    break;
                                                case "log":
                                                    $extn = "Log File";
                                                    break;
                                                case "htm":
                                                    $extn = "HTML File";
                                                    break;
                                                case "php":
                                                    $extn = "PHP Script";
                                                    break;
                                                case "js":
                                                    $extn = "Javascript";
                                                    break;
                                                case "css":
                                                    $extn = "Stylesheet";
                                                    break;
                                                case "pdf":
                                                    $extn = "PDF Document";
                                                    break;

                                                case "zip":
                                                    $extn = "ZIP Archive";
                                                    break;
                                                case "bak":
                                                    $extn = "Backup File";
                                                    break;

                                                default:
                                                    $extn = strtoupper($extn) . " File";
                                                    break;
                                            }

                                            // Separates directories
                                            if (is_dir($dirArray[$index])) {
                                                $extn = "&lt;Directory&gt;";
                                                $size = "&lt;Directory&gt;";
                                                $class = "dir";
                                            } else {
                                                $class = "file";
                                            }

                                            // Cleans up . and .. directories 
                                            if ($name == ".") {
                                                $name = ". (Current Directory)";
                                                $extn = "&lt;System Dir&gt;";
                                            }
                                            if ($name == "..") {
                                                $name = ".. (Parent Directory)";
                                                $extn = "&lt;System Dir&gt;";
                                            }

                                            // Print 'em
                                            print("
                                        <tr class='$class'>
                                            <td><a href='../../../../backup/db/$namehref'>$name</a></td>
                                            <td>$extn</td>
                                            <td>$size</td>
                                            <td sorttable_customkey='$timekey'>$modtime</td>
                                            <td><a href='../../../../backup/db/$namehref'>Download</a></td>
                                            <td><a href='javascript:void(0);' onclick='delete_backup(\"$name\")'><i class='fa fa-trash' aria-hidden='true' style='color:red;'></i></a></td>
                                        </tr>");
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<script>
    function delete_backup(name) {
        $('#cover-spin').show(0);
        if (confirm('Anda yakin akan menghapus file ' + name)) {
            location.href = "<?= site_url('pengaturan/delete/backup/') ?>" + name;
            $.notify({
                title: "Berhasil",
                message: "Backup database berhasil..",
                icon: "success"
            }, {
                type: "success",
            });

        } else {
            $('#cover-spin').hide(0);

        }
    }
    function backup() {
        $('#cover-spin').show(0);
        if (confirm('Anda yakin akan backup Database?')) {
            location.href = "<?= site_url('pengaturan/create/backup') ?>";
            $.notify({
                title: "Berhasil",
                message: "Backup database berhasil..",
                icon: "success"
            }, {
                type: "success",
            });

        } else {
            $('#cover-spin').hide(0);

        }
    }
</script>