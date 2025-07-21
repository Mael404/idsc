let selectedVoice = null;

function loadVoices() {
    const voices = window.speechSynthesis.getVoices();
    selectedVoice = voices.find(voice =>
        voice.name.toLowerCase().includes("zira") ||
        voice.name.toLowerCase().includes("samantha") ||
        voice.name.toLowerCase().includes("google us") ||
        voice.name.toLowerCase().includes("female")
    ) || voices[0];

    console.log("Voice loaded:", selectedVoice.name);
}

export function speak(text) {
    const msg = new SpeechSynthesisUtterance(text);
    msg.voice = selectedVoice;
    msg.lang = 'en-US';
    msg.rate = 1;
    window.speechSynthesis.speak(msg);
}

if (typeof speechSynthesis !== 'undefined' && speechSynthesis.onvoiceschanged !== undefined) {
    speechSynthesis.onvoiceschanged = loadVoices;
}

window.speak = speak;

export function checkBalance() {
    try {
       
    } catch (error) {
        console.log("error: ", error)
    }
}

// global functions
window.checkBalance = checkBalance;