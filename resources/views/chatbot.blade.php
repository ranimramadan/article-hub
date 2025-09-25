<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>شات بوت المقالات</title>
  <script src="https://unpkg.com/alpinejs" defer></script>
  <style>
    body { background:#0f172a; color:#e2e8f0; font-family:system-ui,sans-serif; margin:0; }
    .wrap { max-width:760px; margin:24px auto; padding:16px; }
    .card { background:#111827; border:1px solid #1f2937; border-radius:16px; padding:16px; }
    .msgs { height:60vh; overflow-y:auto; display:flex; flex-direction:column; gap:10px; padding:8px; }
    .bubble { padding:12px 14px; border-radius:14px; white-space:pre-wrap; line-height:1.7; }
    .me { background:#2563eb; color:#fff; align-self:flex-start; border-top-right-radius:4px; }
    .ai { background:#374151; color:#e5e7eb; align-self:flex-end; border-top-left-radius:4px; }
    .row { margin-top:12px; display:flex; gap:8px; }
    .in { flex:1; padding:12px; border:1px solid #334155; background:#0b1220; color:#e2e8f0; border-radius:10px; }
    .btn { padding:12px 16px; background:#22c55e; border:0; border-radius:10px; font-weight:600; cursor:pointer; }
    .btn:disabled { opacity:.6; cursor:not-allowed; }
  </style>
</head>
<body>
<div class="wrap container" x-data="chat()">
  <h2 style="margin:0 0 12px">💬 شات بوت المقالات</h2>
  <div class="card">
    <div class="msgs" x-ref="box">
      <template x-for="m in messages" :key="m.id">
        <div class="bubble" :class="m.role==='user' ? 'me' : 'ai'" x-text="m.text"></div>
      </template>
    </div>
    <div class="row">
      <input class="in" type="text" placeholder="اكتب سؤالك..." x-model="input" @keydown.enter="send()">
      <button class="btn" @click="send()" :disabled="loading">
        <span x-show="!loading">إرسال</span>
        <span x-show="loading">...يكتب</span>
      </button>
    </div>

    <!-- أزرار اختبار (اختياري) -->
    <div style="margin-top:12px; display:flex; gap:8px">
      <button onclick="(async()=>{
        try{ const r=await fetch('/api/ai/ping'); alert('Ping: '+r.status+' '+(await r.text())); }
        catch(e){ alert('Ping error: '+e); }
      })()">اختبار /api/ai/ping</button>

      <button onclick="(async()=>{
        try{
          const r=await fetch('/api/ai/chat',{
            method:'POST',
            headers:{'Content-Type':'application/json','Accept':'application/json'},
            body: JSON.stringify({message:'اختبار زر', history:[]})
          });
          const j=await r.json().catch(()=> ({}));
          alert('Chat status: '+r.status+'\n'+JSON.stringify(j));
        }catch(e){ alert('Chat error: '+e); }
      })()">اختبار /api/ai/chat</button>
    </div>
  </div>
</div>

<script>
window.chat = function () {
  return {
    input: '',
    loading: false,
    messages: [
      { id: crypto.randomUUID(), role:'assistant', text:'أهلاً! اسألني عن مقالات موقعنا 👋' }
    ],
    async send() {
      const text = this.input.trim();
      if (!text || this.loading) return;

      this.messages.push({ id: crypto.randomUUID(), role:'user', text });
      this.input = '';
      this.loading = true;
      this.$nextTick(() => this.$refs.box.scrollTop = this.$refs.box.scrollHeight);

      const history = this.messages.slice(-6).map(m => ({
        role: m.role === 'user' ? 'user' : 'assistant',
        text: m.text
      }));

      
      try {
        const res = await fetch('/api/ai/chat', {
          method: 'POST',
          headers: { 'Content-Type':'application/json', 'Accept':'application/json' },
          body: JSON.stringify({ message: text, history })
        });
        const data = await res.json().catch(() => ({}));
        const reply = data.reply ?? (data.error ? `خطأ: ${data.error}` : 'ما في رد.');
        this.messages.push({ id: crypto.randomUUID(), role:'assistant', text: reply });
      } catch (e) {
        this.messages.push({ id: crypto.randomUUID(), role:'assistant', text:'فشل الاتصال بالخادم.' });
        console.error(e);
      } finally {
        this.loading = false;
        this.$nextTick(() => this.$refs.box.scrollTop = this.$refs.box.scrollHeight);
      }
    }
  }
};
</script>
</body>
</html>
