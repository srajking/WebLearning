function speakText() {
    const text = document.getElementById('textInput').value;
    
    if (text) {
        const utterance = new SpeechSynthesisUtterance(text);
        speechSynthesis.speak(utterance);
        
        // هنا يمكنك إضافة الترجمة
        const translation = translateText(text); // استبدل هذه الدالة بتطبيق الترجمة الخاص بك
        document.getElementById('translationOutput').innerText = translation;
    } else {
        alert("يرجى كتابة نص للاستماع إليه.");
    }
}

// دالة بسيطة لترجمة النص (استبدلها بترجمة حقيقية)
function translateText(text) {
    // مثال بسيط لترجمة نص من العربية إلى الإنجليزية
    const translations = {
        "مرحبا": "Hello",
        "كيف حالك؟": "How are you?",
        "أحب البرمجة": "I love programming",
        // أضف المزيد من الترجمات حسب الحاجة
    };
    
    return translations[text] || "لا توجد ترجمة متاحة.";
}
