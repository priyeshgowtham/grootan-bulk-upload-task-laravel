<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <title>Laravel File Uploader</title>
    <style>
        .upload{
            height: 90vh;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container justify-content-center">
            <span class="navbar-brand mb-0 h1">Laravel Bulk-File Uploader</span>
        </div>
    </nav>
    <div class="upload container">
        <div class="d-flex flex-column align-items-center h-100 justify-content-center">
            <form action="/" id="uploadForm" class="text-center" method="POST" enctype="multipart/form-data">
                <input class="form-control" id="files" type="file" name="mycsv" required>
                <input class="mt-3 btn btn-primary" type="submit" name="Upload" >
            </form>
            <h5 class="upload-progress"></h5>
            <progress id="file" value="0" max="100"> 100% </progress>
        </div>
        <script>
            uploadForm.onsubmit=async(e)=>{
                e.preventDefault();
                const formData=new FormData(document.getElementById("uploadForm"))
                await fetch('http://127.0.0.1:8000/api/upload',{
                    method:'post',
                    body:formData
                }).then(async (res)=>{
                    let result=await res.json()
                    if(result.id != ''){
                        $('#uploadForm').hide();
                        $('progress').show();
                        $('.upload-progress').show();
                        let myinterval=setInterval(async() => {
                            await fetch(`http://127.0.0.1:8000/api/batch?id=${result.id}`)
                            .then(async (res)=>{  
                                let response=await res.json()
                                if(response.progress!=100){
                                    $('.upload-progress').html(`Upload in Progress ${response.progress}%`)
                                    $('progress').val(response.progress)
                                }
                                else{
                                    $('progress').hide();
                                    $('.upload-progress').hide();
                                    alert('Upload Successful')
                                    $('#uploadForm').show();
                                    clearInterval(myinterval)
                                }  
                            })
                        }, 2000);                        
                    }
                })
                // let result=await res.json()
                // console.log(await res.json())
            }
        </script>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
        jQuery(document ).ready(function() {
            $('progress').hide();
            $('.upload-progress').hide();
        });
    </script>
</body>
</html>