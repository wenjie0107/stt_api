<template>
    <div class="container">
        <div class="recording-controls">
            <div class="row">
                <div class="col-4">
                    <!-- <form id="formElement"> -->
                        <button id="start" @click="actionBtn">{{ word }}</button>
                        <!-- <button id="stop" disabled>Stop recording</button> -->
                        <audio id="audio" controls style="display: none;"></audio>
                    <!-- </form> -->
                </div>
                <div class="col-8">
                    <p class="whisper_response_display_area" id="whisper_response_display_area">{{ result }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

let word = ref("Start")
let chunks = ref([])
let mediaRecorder = ref()
let toggleBtn = ref(false)
let result = ref()

function actionBtn(){
    //toggle the btn start or stop
    toggleBtn.value = !toggleBtn.value
    //update the btn text
    if(toggleBtn.value)
    {
        word.value = "Recording.."
    }
    else{
        word.value = "Start"
    }
    
    if(toggleBtn.value)
    {
        //ask for mic permissions
        navigator.mediaDevices.getUserMedia({ audio: true }).then((stream) => {
        mediaRecorder = new MediaRecorder(stream, { mimeType: "audio/webm" })
        mediaRecorder.start()

        mediaRecorder.ondataavailable = (e) => {
        chunks.value.push(e.data)
        }

        mediaRecorder.onstop = (e) => {
            let blob = new Blob(chunks.value, { type: "audio/webm" })
            chunks = ref([])
            let audioURL = URL.createObjectURL(blob)
            document.getElementById("audio").src = audioURL

            // Create a new FormData object.
            var formData = new FormData()

            // Create a blob file object from the blob.
            var file = new File([blob], "user_audio.webm", {
                type: "audio/webm",
            });

            // Append the audio file to the form data.
            formData.append("audio", file)

            console.log("Sending audio file to server...")
            axios.post('/post_audio_file',formData, {
                'Content-type': 'multipart/form-data',
            })
            .then((response) => {
                result.value = response['data']['text']
            })
            .catch(function (error) {
                console.error("Error sending audio file to server:", error)
            });
        };

        console.log("Recording started...")
    
        });
    }
    else
    {
        if (mediaRecorder) {
            mediaRecorder.stop()
        }
        console.log("Recording stopped...")
    }
};

</script>
