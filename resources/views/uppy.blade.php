
    <style>
        .uppy-Dashboard-inner {
            width: 100% !important;
            height: 400px !important;
        }
        .order_document {
            text-align: center;
            position: absolute;
            top: -32px;
            left: 0;
            right: 0;
            margin: 0 auto;
            font-weight: 700;
            font-size: 22px;
            background-color: rgb(45, 124, 243);
            height: 60px;
            display: inline-block;
            width: 60px;
            line-height: 62px;
            border-radius: 50%;
            color: #fff !important;
            box-shadow: 0px 0px 9px #0f0f0f5e;
        }
        .order_document_check{
            color: #58de84 !important;
            background-color: rgb(253, 255, 255);
            border: 2px solid #58de84;
        }

        .imagen_check {
            border-radius: 9px;
        }
        .imagen_bill {
            width: 100% !important;
            border-radius: 9px;
        }
        .top_seller {
            animation: pulse-seller 3s infinite;
        }
        .uppy-size--xl .uppy-Dashboard-Item {
            height: 223px !important;
            width: 345px !important;
            margin: 0 auto !important;
        }
        .one-document img {
            width: 400px !important;
        }
        .uppy-size--xl .uppy-Dashboard-Item-preview {
            height: 220px !important;
        }
        [dir="ltr"] .uppy-size--md .uppy-Dashboard-Item{
            float:initial !important;
        }
        @media screen and (max-width: 768px) {
            #imagen_bill {
                width: 100%;
                height: 100%;
            }
            .imagen_check {
                margin: 0 0 15px 0;
            }
        }

        @keyframes pulse-seller {
            0% {
                transform: scale(1);
                text-shadow: 0 0 0 rgb(17, 13, 13);
            }

            70% {
                transform: scale(1);
                text-shadow: 2px 2px 30px rgba(236, 16, 16, 0);
            }

            100% {
                transform: scale(1);
                text-shadow: 0 0 0 rgba(202, 17, 17, 0);
            }
        }
    </style>
    <link href="/css/uppy.min.css" rel="stylesheet">
    <div class="row" style="margin: 0px 0 35px 0;">
        <div class="col-md-12" style="margin: 0 0 50px 0;">
            <h2 class="text-center" style="text-transform: uppercase;">Upload Documents</h2>
            <p class="text-center top_seller" style="text-transform: uppercase;"><b>It is necessary that you upload the documents in the indicated order</b></p>
        </div>
        @foreach ($document as $key => $row)

                <div class="{{ $row["col-md"] }} text-center" id="{{ $row["document_id"] }}">
                    <div class="card" style="padding: 10px; border-radius: 15px;">
                        <div class="card-body">
                            <h4 class="card-title" style="margin: 11px 0 0 0;">{{ $row["title"] ?? "" }}</h4>
                        </div>
                        <img src="{{ $row["src"] }}" id="{{ $row["id_img"] }}" width="100%" height="230px">
                        <h1 class="order_document {{ $row["order_document"] }}" id="order_document{{ $key }}">{!! $row["span"] !!}</h1>
                    </div>
                </div>
        @endforeach
    </div>
    <div id="drag-drop-area" class="{{ $upload_img }}"></div>
    <input type="hidden" name="data_email_document" id="data_email_document">
    @csrf

    <script src="/js/uppy.min.js"></script>
    <script>
        var customer = document.getElementById("data_email_document");
        var email = "{{ $email }}"||"";
        var document_type = {!! $document_type !!}||"";
        var cont = 0;
        var token = "{{ csrf_token() }}" ;

        var uppy = new Uppy.Core({
            debug: true,
            autoProceed: false,
            restrictions :  {
                maxFileSize :  2048576,
                maxTotalFileSize :  2048576,
                maxNumberOfFiles :  1 ,
                minNumberOfFiles : 1,
                allowedFileTypes :  ['.jpg', '.jpeg', '.png', '.gif']
            }
        })
        .use(Uppy.Dashboard, {
            inline: true,
            target: '#drag-drop-area',
            trigger: '.UppyModalOpenerBtn',
            showProgressDetails: true,
            note: 'Images only, 3 files, down to 1 MB',
            height: 200,
            browserBackButtonClose: false,
            autoOpenFileEditor: true,
            closeModalOnClickOutside: true,
            onRequestCloseModal: () => this.doneButtonHandler()
        })
        .use(Uppy.Webcam, {
            countdown: false,
            modes: [
                'video-audio',
                'video-only',
                'audio-only',
                'picture',
            ],
            mirror: true,
            videoConstraints: {
                facingMode: 'user',
                width: { min: 720, ideal: 1280, max: 1920 },
                height: { min: 480, ideal: 800, max: 1080 },
            },
            showRecordingLength: false,
            target: Uppy.Dashboard // Webcam will be installed to the Dashboard
        })
        .use(Uppy.ImageEditor, {
            id: 'ImageEditor',
            quality: 0.8,
            cropperOptions: {
              viewMode: 1,
              background: false,
              autoCropArea: 1,
              responsive: true,
              croppedCanvasOptions: {},
            },
            actions: {
              revert: true,
              rotate: true,
              granularRotate: true,
              flip: true,
              zoomIn: true,
              zoomOut: true,
              cropSquare: true,
              cropWidescreen: true,
              cropWidescreenVertical: true,
            },
            target: Uppy.Dashboard // Webcam will be installed to the Dashboard
        })
        .use(Uppy.XHRUpload, {
            endpoint: '/upload-image',
            method: 'post',
            formData: true,
            fieldName: "file",
        })
        uppy.on('file-added', (file) =>{
            uppy.setFileMeta(file.id, {
                _token: token,
                email: email,
                type: document_type[cont],
            });
        });
        uppy.on('upload-success', (file, response) => {
            let img = '/storage/CP/'+response.body.data
            if(document_type[cont] == "id"){
                $(".order_document_id").html('<i class="mdi mdi-check-all"></i>');
                $(".order_document_id").addClass("order_document_check");
                $("#imagen_id").addClass("imagen_check");
                $("#imagen_id").attr('src', img);
            }
            if(document_type[cont] == "ssn"){
                $(".order_document_ssn").html('<i class="mdi mdi-check-all"></i>');
                $(".order_document_ssn").addClass("order_document_check");
                $(".order_document_ssn").removeClass("order_document_ssn");
                $("#imagen_social").addClass("imagen_check");
                $("#imagen_social").attr('src', img);
            }
            if(document_type[cont] == "bill"){
                $(".order_document_bill").html('<i class="mdi mdi-check-all"></i>');
                $(".order_document_bill").addClass("order_document_check");
                $(".order_document_bill").removeClass("order_document_bill");
                $("#imagen_bill").addClass("imagen_bill");
                $("#imagen_bill").attr('src', img);
            }
            if(document_type.length == cont + 1){
                $("#drag-drop-area").addClass("d-none");
            }
        })
        uppy.on('upload-error', (file, error, response) => {
            if(response.status == 400){
                $(function(){
                    $.NotificationApp.send("Upload Incorrect!", response.body.error, 'top-right', '#bf441d', 'error');
                });
            }
        });

        function doneButtonHandler() {
            cont++;
        }
        </script>

