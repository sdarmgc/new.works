<x-app-layout>

{{--
  The <x-slot name="header"> is intentionally omitted — our own topbar shows the title,
  which lets us use a clean calc(100vh - 4rem) (just the navbar height).
--}}

{{--
  /*
  * .ms-shell fills exactly the viewport below Jetstream's navbar (h-16 = 4rem = 64px).
  * No <x-slot name="header"> is used, so nothing else to subtract.
  *
  * THEMING: vars are defined on .ms-shell (and .ms-modal / .ms-toast for elements
  * that live outside .ms-shell in the DOM).  Switching between light and dark is
  * handled entirely in CSS by reacting to  html.dark  — exactly what Jetstream 5
  * adds/removes when the user toggles the theme.
  */
--}}

{{-- ── LIGHT THEME (default, html:not(.dark)) ────────────────────────── --}}

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/2.0.2/quill.snow.min.css" rel="stylesheet" />
<style>
  @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

  .ms-shell,
  .ms-modal,
  .ms-toast {
    --ms-bg:        #ffffff;
    --ms-surface-1: #f8fafc;
    --ms-surface-2: #f1f5f9;
    --ms-surface-3: #e8edf2;
    --ms-border:    #e2e8f0;
    --ms-border-2:  #cbd5e1;
    --ms-text-1:    #0f172a;
    --ms-text-2:    #475569;
    --ms-text-3:    #94a3b8;
    --ms-accent:    #d97706;
    --ms-accent-dim:#fef3c7;
    --ms-green:     #16a34a;
    --ms-red:       #dc2626;
    --ms-blue:      #2563eb;
    --ms-shadow:    rgba(0, 0, 0, .10);
  }

  /* ── DARK THEME (html.dark) ─────────────────────────────────────────── */
  html.dark .ms-shell,
  html.dark .ms-modal,
  html.dark .ms-toast {
    --ms-bg:        #0d1117;
    --ms-surface-1: #161b22;
    --ms-surface-2: #1c2430;
    --ms-surface-3: #21262d;
    --ms-border:    #30363d;
    --ms-border-2:  #3d444d;
    --ms-text-1:    #f0f6fc;
    --ms-text-2:    #8b949e;
    --ms-text-3:    #656d76;
    --ms-accent:    #f0a500;
    --ms-accent-dim:#7a5200;
    --ms-green:     #3fb950;
    --ms-red:       #f85149;
    --ms-blue:      #58a6ff;
    --ms-shadow:    rgba(0, 0, 0, .50);
  }

  .ms-shell {
    height: calc(100vh - 10rem);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    background: var(--ms-bg);
  }

  /* ── TOP BAR ── */
  .ms-topbar {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 20px;
    border-bottom: 1px solid var(--ms-border);
    background: var(--ms-surface-1);
    flex-shrink: 0;
    color: var(--ms-text-1);
  }
  .ms-topbar-title { font-size: 15px; font-weight: 700; color: var(--ms-text-1); }
  .ms-topbar-sub   { font-size: 13px; color: var(--ms-text-3); }
  .ms-topbar-right { margin-left: auto; display: flex; gap: 8px; }

  .ms-btn {
    padding: 6px 14px; border-radius: 7px; border: 1px solid var(--ms-border);
    background: transparent; color: var(--ms-text-2); font-size: 13px;
    font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .12s;
    display: inline-flex; align-items: center; gap: 6px; line-height: 1.4;
  }
  .ms-btn:hover   { border-color: var(--ms-border-2); color: var(--ms-text-1); background: var(--ms-surface-2); }
  .ms-btn.primary { background: var(--ms-accent); border-color: var(--ms-accent); color: #1a0e00; font-weight: 700; }
  .ms-btn.primary:hover { opacity: .88; }

  /* ── TEMPLATE CHIP STRIP ── */
  .ms-template-strip {
    background: var(--ms-surface-1);
    border-bottom: 1px solid var(--ms-border);
    padding: 8px 0; flex-shrink: 0;
  }
  .ms-strip-inner {
    display: flex; align-items: center; gap: 8px;
    padding: 0 12px; overflow-x: auto; scrollbar-width: none;
  }
  .ms-strip-inner::-webkit-scrollbar { display: none; }
  .ms-strip-label {
    font-size: 11px; font-weight: 600; letter-spacing: 1.2px;
    text-transform: uppercase; color: var(--ms-text-3);
    white-space: nowrap; flex-shrink: 0;
  }
  .ms-chip {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; border-radius: 100px;
    border: 1px solid var(--ms-border); background: var(--ms-surface-2);
    cursor: pointer; transition: all .15s; white-space: nowrap; flex-shrink: 0;
    font-size: 13px; font-weight: 500; color: var(--ms-text-2);
    font-family: 'DM Sans', sans-serif;
  }
  .ms-chip:hover  { border-color: var(--ms-accent-dim); color: var(--ms-accent); background: rgba(240,165,0,.07); }
  .ms-chip.active { border-color: var(--ms-accent); color: var(--ms-accent); background: rgba(240,165,0,.12); }

  /* ── COMPOSE SCROLL AREA (grows to fill remaining height) ── */
  .ms-compose-area {
    flex: 1;
    min-height: 0;        /* critical: lets flex child shrink below content size */
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    background: var(--ms-bg);
  }

  /* ── RECIPIENT FIELDS ── */
  .ms-field-row {
    display: flex; align-items: center;
    border-bottom: 1px solid var(--ms-border);
    min-height: 44px; flex-shrink: 0;
    background: var(--ms-bg);
  }
  .ms-field-row:first-child { border-top: 1px solid var(--ms-border); }
  .ms-field-label {
    width: 56px; flex-shrink: 0;
    font-family: 'DM Mono', monospace; font-size: 11px;
    font-weight: 500; color: var(--ms-text-3); letter-spacing: .5px;
    padding: 0 0 0 14px; text-transform: uppercase;
  }
  .ms-field-input {
    flex: 1; background: transparent; border: none; outline: none;
    padding: 12px 0; font-family: 'DM Sans', sans-serif;
    font-size: 14px; color: var(--ms-text-1); line-height: 1.4;
  }
  .ms-field-input::placeholder { color: var(--ms-text-3); }
  .ms-field-actions { display: flex; align-items: center; gap: 6px; padding: 0 12px; }
  .ms-toggle {
    padding: 2px 8px; border-radius: 5px; border: 1px solid var(--ms-border);
    background: transparent; color: var(--ms-text-3); font-size: 11px;
    font-family: 'DM Mono', monospace; cursor: pointer; transition: all .12s;
  }
  .ms-toggle:hover  { color: var(--ms-text-2); border-color: var(--ms-border-2); }
  .ms-toggle.active { color: var(--ms-accent); border-color: var(--ms-accent-dim); background: rgba(240,165,0,.08); }

  /* ── EDITOR WRAPPER ── */
  .ms-editor-wrap {
    flex: 1;
    min-height: 0;        /* critical: same reason as ms-compose-area */
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border-top: 1px solid var(--ms-border);
  }

  /* Quill toolbar */
  .ql-toolbar.ql-snow {
    border: none !important;
    border-bottom: 1px solid var(--ms-border) !important;
    padding: 8px 12px !important;
    background: var(--ms-surface-1) !important;
    flex-shrink: 0;
  }
  .ql-toolbar.ql-snow .ql-stroke { stroke: var(--ms-text-3) !important; }
  .ql-toolbar.ql-snow .ql-fill   { fill:  var(--ms-text-3) !important; }
  .ql-toolbar.ql-snow button:hover .ql-stroke,
  .ql-toolbar.ql-snow .ql-picker-label:hover .ql-stroke { stroke: var(--ms-text-1) !important; }
  .ql-toolbar.ql-snow button:hover .ql-fill,
  .ql-toolbar.ql-snow .ql-picker-label:hover .ql-fill   { fill:  var(--ms-text-1) !important; }
  .ql-toolbar.ql-snow .ql-active .ql-stroke { stroke: var(--ms-accent) !important; }
  .ql-toolbar.ql-snow .ql-active .ql-fill   { fill:  var(--ms-accent) !important; }
  .ql-toolbar.ql-snow .ql-picker-label      { color:  var(--ms-text-3) !important; }
  .ql-toolbar.ql-snow .ql-picker-label:hover { color: var(--ms-text-1) !important; }
  .ql-picker-options { background: var(--ms-surface-2) !important; border-color: var(--ms-border) !important; }
  .ql-picker-item    { color: var(--ms-text-2) !important; }
  .ql-picker-item:hover { color: var(--ms-text-1) !important; background: var(--ms-surface-3) !important; }

  /* Quill container — must be flex:1 so the editor fills the remaining height */
  .ql-container.ql-snow {
    border: none !important;
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
    background: var(--ms-bg);
  }
  .ql-editor {
    flex: 1;
    overflow-y: auto !important;
    font-family: 'DM Sans', sans-serif !important;
    font-size: 15px !important;
    color: var(--ms-text-1) !important;
    line-height: 1.75 !important;
    padding: 20px 24px !important;
  }
  .ql-editor.ql-blank::before {
    color: var(--ms-text-3) !important;
    font-style: normal !important;
    left: 24px !important;
  }
  .ql-editor a { color: var(--ms-blue) !important; }

  /* ── ATTACHMENT BAR ── */
  .ms-attach-bar {
    display: flex; align-items: center; flex-wrap: wrap; gap: 8px;
    padding: 8px 14px;
    border-top: 1px solid var(--ms-border);
    background: var(--ms-surface-1); flex-shrink: 0;
  }
  .ms-attach-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 11px; border-radius: 7px;
    border: 1px dashed var(--ms-border); background: transparent;
    color: var(--ms-text-3); font-size: 13px;
    font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .12s;
  }
  .ms-attach-btn:hover { border-color: var(--ms-accent-dim); color: var(--ms-accent); }
  .ms-attach-chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 9px; border-radius: 6px;
    background: var(--ms-surface-3); border: 1px solid var(--ms-border);
    font-size: 12px; color: var(--ms-text-2);
  }
  .ms-attach-chip button {
    background: none; border: none; cursor: pointer;
    color: var(--ms-text-3); font-size: 14px; line-height: 1; padding: 0;
  }
  .ms-attach-chip button:hover { color: var(--ms-red); }

  /* ── ACTION BAR ── */
  .ms-action-bar {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 20px;
    border-top: 1px solid var(--ms-border);
    background: var(--ms-surface-1); flex-shrink: 0;
  }
  .ms-send-btn {
    padding: 9px 26px; border-radius: 8px; border: none;
    background: var(--ms-accent); color: #1a0e00;
    font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 700;
    cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
    transition: opacity .12s, transform .1s;
  }
  .ms-send-btn:hover    { opacity: .88; transform: translateY(-1px); }
  .ms-send-btn:active   { transform: translateY(0); }
  .ms-send-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; }
  .ms-action-sep { flex: 1; }
  .ms-icon-btn {
    padding: 7px; border-radius: 7px; border: 1px solid transparent;
    background: transparent; color: var(--ms-text-3); cursor: pointer;
    font-size: 15px; transition: all .12s; line-height: 1; display: inline-flex;
  }
  .ms-icon-btn:hover        { background: var(--ms-surface-2); border-color: var(--ms-border); color: var(--ms-text-1); }
  .ms-icon-btn.danger:hover { color: var(--ms-red); border-color: rgba(248,81,73,.3); background: rgba(248,81,73,.07); }
  .ms-save-status {
    font-family: 'DM Mono', monospace; font-size: 11px;
    color: var(--ms-text-3); letter-spacing: .4px;
  }
  .ms-spinner {
    width: 14px; height: 14px; flex-shrink: 0;
    border: 2px solid rgba(240,165,0,.25);
    border-top-color: var(--ms-accent);
    border-radius: 50%; animation: ms-spin .6s linear infinite; display: none;
  }
  .ms-spinner.visible { display: block; }
  @keyframes ms-spin { to { transform: rotate(360deg); } }

  /* ── TOAST ── */
  .ms-toast {
    position: fixed; bottom: 24px; left: 50%;
    transform: translateX(-50%) translateY(12px);
    background: var(--ms-surface-2); border: 1px solid var(--ms-border);
    padding: 11px 20px; border-radius: 10px;
    font-size: 13px; font-weight: 500; color: var(--ms-text-1);
    display: inline-flex; align-items: center; gap: 9px;
    box-shadow: 0 8px 32px var(--ms-shadow);
    opacity: 0; transition: all .25s; pointer-events: none;
    z-index: 9999; white-space: nowrap;
  }
  .ms-toast.show    { opacity: 1; transform: translateX(-50%) translateY(0); pointer-events: all; }
  .ms-toast.success .ms-toast-icon { color: var(--ms-green); }
  .ms-toast.error   .ms-toast-icon { color: var(--ms-red);   }
  .ms-toast.info    .ms-toast-icon { color: var(--ms-blue);  }

  /* ── TEMPLATE MODAL ── */
  .ms-modal-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.45);
    display: flex; align-items: center; justify-content: center;
    z-index: 9000; opacity: 0; pointer-events: none; transition: opacity .2s;
  }
  html.dark .ms-modal-overlay {
    background: rgba(0,0,0,.65);
  }
  .ms-modal-overlay.open { opacity: 1; pointer-events: all; }
  .ms-modal {
    background: var(--ms-surface-1); border: 1px solid var(--ms-border);
    border-radius: 14px; width: 700px; max-width: calc(100vw - 48px);
    max-height: 80vh; display: flex; flex-direction: column;
    transform: translateY(14px) scale(.97); transition: transform .2s; overflow: hidden;
    font-family: 'DM Sans', sans-serif;
  }
  .ms-modal-overlay.open .ms-modal { transform: translateY(0) scale(1); }
  .ms-modal-header {
    display: flex; align-items: center;
    padding: 18px 22px; border-bottom: 1px solid var(--ms-border);
  }
  .ms-modal-header h2 { font-size: 15px; font-weight: 700; color: var(--ms-text-1); flex: 1; margin: 0; }
  .ms-modal-close {
    padding: 5px 8px; border-radius: 6px; border: none; background: transparent;
    color: var(--ms-text-3); font-size: 17px; cursor: pointer; line-height: 1;
  }
  .ms-modal-close:hover { color: var(--ms-text-1); background: var(--ms-surface-2); }
  .ms-modal-body   { padding: 20px; overflow-y: auto; flex: 1; }
  .ms-modal-footer {
    display: flex; justify-content: flex-end; gap: 10px;
    padding: 14px 22px; border-top: 1px solid var(--ms-border);
  }
  .ms-tpl-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 14px; }
  .ms-tpl-card {
    border: 1px solid var(--ms-border); border-radius: 10px;
    overflow: hidden; cursor: pointer; transition: all .15s; background: var(--ms-surface-2);
  }
  .ms-tpl-card:hover    { border-color: var(--ms-accent); box-shadow: 0 0 0 1px var(--ms-accent-dim); }
  .ms-tpl-card.selected { border-color: var(--ms-accent); box-shadow: 0 0 0 2px var(--ms-accent); }
  .ms-tpl-preview { height: 120px; overflow: hidden; pointer-events: none; background: #fff; }
  .ms-tpl-preview iframe {
    width: 200%; height: 240px; border: none;
    transform: scale(.5); transform-origin: 0 0; pointer-events: none;
  }
  .ms-tpl-meta { padding: 10px 12px; }
  .ms-tpl-meta .tpl-name    { font-size: 13px; font-weight: 600; color: var(--ms-text-1); margin-bottom: 3px; }
  .ms-tpl-meta .tpl-subject { font-size: 11px; color: var(--ms-text-3); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
</style>
@endpush

{{-- ══════════════════════════════════════════════
     COMPOSE UI
     ══════════════════════════════════════════════ --}}
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

          <div class="ms-shell">

            {{-- TOP BAR --}}
            <div class="ms-topbar">
              <!--span class="ms-topbar-title">New Message</span-->
              <!--span class="ms-topbar-sub"> &mdash; Compose</span-->
              <div class="ms-topbar-right">
                <button class="ms-btn" onclick="msOpenTemplateModal()">🗂 &nbsp;Templates</button>
                <button class="ms-btn" onclick="msSaveDraft()">💾 &nbsp;Save Draft</button>
                <button class="ms-btn primary" onclick="msSendEmail()">Send &nbsp;&rarr;</button>
              </div>
            </div>

            {{-- TEMPLATE CHIP STRIP --}}
            <div class="ms-template-strip">
              <div class="ms-strip-inner">
                <span class="ms-strip-label">Templates:</span>
                @foreach($templates as $tpl)
                <button class="ms-chip" data-name="{{ $tpl['name'] }}"
                        onclick="msSelectTemplate('{{ $tpl['name'] }}', this)">
                  <span>
                    @switch($tpl['name'])
                      @case('welcome')            🙌 @break
                      @case('newsletter')         📰 @break
                      @default                    📄
                    @endswitch
                  </span>
                  {{ $tpl['label'] }}
                </button>
                @endforeach
                <button class="ms-chip" style="border-style:dashed" onclick="msClearTemplate()">
                  ✕ Clear
                </button>
              </div>
            </div>

            {{-- FIELDS + EDITOR --}}
            <div class="ms-compose-area">

              {{-- TO --}}
              <div class="ms-field-row">
                <span class="ms-field-label">To</span>
                <input id="ms-to" type="text" class="ms-field-input" value="{{ $addresses }}"
                      placeholder="recipient@example.com, another@example.com" autocomplete="off" />
                <div class="ms-field-actions">
                  <button class="ms-toggle" id="msCcBtn"  onclick="msToggleField('cc')">Cc</button>
                  <button class="ms-toggle" id="msBccBtn" onclick="msToggleField('bcc')">Bcc</button>
                </div>
              </div>

              {{-- CC --}}
              <div class="ms-field-row" id="msCcRow" style="display:none">
                <span class="ms-field-label">Cc</span>
                <input id="ms-cc" type="text" class="ms-field-input"
                      placeholder="cc@example.com" autocomplete="off" />
              </div>

              {{-- BCC --}}
              <div class="ms-field-row" id="msBccRow" style="display:none">
                <span class="ms-field-label">Bcc</span>
                <input id="ms-bcc" type="text" class="ms-field-input"
                      placeholder="bcc@example.com" autocomplete="off" />
              </div>

              {{-- SUBJECT --}}
              <div class="ms-field-row">
                <span class="ms-field-label">Subj</span>
                <input id="ms-subject" type="text" class="ms-field-input"
                      placeholder="Subject" autocomplete="off" />
              </div>

              {{-- QUILL --}}
              <div class="ms-editor-wrap">
                <div id="ms-editor"></div>
              </div>

            </div>{{-- /ms-compose-area --}}

            {{-- ATTACHMENT BAR --}}
            <div class="ms-attach-bar">
              <button class="ms-attach-btn" onclick="document.getElementById('msFileInput').click()">
                📎 &nbsp;Attach file
              </button>
              <input type="file" id="msFileInput" multiple style="display:none"
                    onchange="msHandleAttachments(this.files)" />
              <div id="msAttachList" style="display:contents"></div>
              <span class="ms-save-status" id="msSaveStatus" style="margin-left:auto"></span>
              <div class="ms-spinner" id="msSpinner"></div>
            </div>

            {{-- ACTION BAR --}}
            <div class="ms-action-bar">
              <button class="ms-send-btn" id="msSendBtn" onclick="msSendEmail()">
                Send &nbsp;&rarr;
              </button>
              <button class="ms-btn" title="Save draft"  onclick="msSaveDraft()">💾 &nbsp;Save Draft</button>
              <button class="ms-btn" title="Attach file" onclick="document.getElementById('msFileInput').click()">📎 &nbsp;Attach File</button>
              <!-- button class="ms-icon-btn" title="Schedule (demo)">🕐</button -->
              <div class="ms-action-sep"></div>
              <button class="ms-btn danger" title="Discard" onclick="msDiscardDraft()">🗑 &nbsp;Discard</button>
            </div>

          </div>{{-- /ms-shell --}}

        </div>
    </div>
</div>

{{-- Modal + Toast live outside ms-shell so they layer over the Jetstream nav correctly --}}
<div class="ms-modal-overlay" id="msTplModal" onclick="msCloseModalOnOverlay(event)">
  <div class="ms-modal">
    <div class="ms-modal-header">
      <h2>Choose a Template</h2>
      <button class="ms-modal-close" onclick="msCloseTemplateModal()">✕</button>
    </div>
    <div class="ms-modal-body">
      <div class="ms-tpl-grid">
        @foreach($templates as $tpl)
        <div class="ms-tpl-card" id="msCard-{{ $tpl['name'] }}"
            data-name="{{ $tpl['name'] }}"
            onclick="msPreviewTemplate('{{ $tpl['name'] }}', this)">
          <div class="ms-tpl-preview">
            <iframe sandbox="allow-same-origin" loading="lazy" title="{{ $tpl['label'] }}"></iframe>
          </div>
          <div class="ms-tpl-meta">
            <div class="tpl-name">{{ $tpl['label'] }}</div>
            <div class="tpl-subject">{{ $tpl['subject'] ?: 'No subject' }}</div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    <div class="ms-modal-footer">
      <button class="ms-btn" onclick="msCloseTemplateModal()">Cancel</button>
      <button class="ms-btn primary" id="msApplyTplBtn"
              onclick="msApplySelectedTemplate()" disabled>Apply Template &rarr;</button>
    </div>
  </div>
</div>

<div class="ms-toast" id="msToast">
  <span class="ms-toast-icon" id="msToastIcon"></span>
  <span id="msToastMsg"></span>
</div>

{{-- Quill JS — inline because Jetstream's layout has no @stack('scripts') by default --}}
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/2.0.2/quill.min.js"></script>
<script>
  // ── QUILL ─────────────────────────────────────────────────────────────
  const msQuill = new Quill('#ms-editor', {
    theme: 'snow',
    placeholder: 'Write your message, or pick a template above…',
    modules: {
      toolbar: [
        [{ header: [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ color: [] }, { background: [] }],
        [{ align: [] }],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['blockquote'/*, 'code-block'*/],
        ['link', 'image'],
        ['clean'],
      ],
    },
  });

  // Apply flex layout to Quill's generated elements so the editor fills its parent
  (function () {
    const toolbar   = document.querySelector('.ms-editor-wrap .ql-toolbar');
    const container = document.querySelector('.ms-editor-wrap .ql-container');
    const editor    = document.querySelector('.ms-editor-wrap .ql-editor');

    if (container) {
      container.style.cssText += '; flex:1; min-height:0; display:flex; flex-direction:column; overflow:hidden;';
    }
    if (editor) {
      editor.style.cssText += '; flex:1; min-height:0; overflow-y:auto;';
    }
  })();

  // Show Quill button tooltips on hover (since we're not showing them on mobile)
  const buttonTitles = {
    'bold': 'Bold',
    'italic': 'Italic',
    'underline': 'Underline',
    'strike': 'Strikethrough',
    'color': 'Text Color',
    'background': 'Background Color',
    'align': 'Text Alignment',
    'link': 'Link',
    'image': 'Image',
    'list[value="ordered"]': 'Ordered List',
    'list[value="bullet"]': 'Bullet List',
    'blockquote': 'Block Quote',
    'clean': 'Remove Formatting',
  };

  const toolbar = document.querySelector('.ql-toolbar');
  Object.keys(buttonTitles).forEach(selector => {
    const btn = toolbar.querySelector(`.ql-${selector}`);
    if (btn) btn.setAttribute('title', buttonTitles[selector]);
  });

  // ── STATE ─────────────────────────────────────────────────────────────
  let msAttachedFiles   = [];
  let msSelectedTplName = null;
  const msCsrf = () => document.querySelector('meta[name="csrf-token"]').content;

  // ── CC / BCC ──────────────────────────────────────────────────────────
  function msToggleField(field) {
    const cap     = field[0].toUpperCase() + field.slice(1);
    const row     = document.getElementById('ms' + cap + 'Row');
    const btn     = document.getElementById('ms' + cap + 'Btn');
    const visible = row.style.display !== 'none';
    row.style.display = visible ? 'none' : 'flex';
    btn.classList.toggle('active', !visible);
    if (!visible) document.getElementById('ms-' + field).focus();
  }

  // ── TEMPLATE STRIP ────────────────────────────────────────────────────
  async function msSelectTemplate(name, chipEl) {
    document.querySelectorAll('.ms-chip').forEach(c => c.classList.remove('active'));
    chipEl.classList.add('active');
    await msLoadAndApply(name);
  }
  function msClearTemplate() {
    document.querySelectorAll('.ms-chip').forEach(c => c.classList.remove('active'));
    msQuill.setContents([]);
    msSetStatus('Cleared', '🗑');
  }

  // ── TEMPLATE MODAL ────────────────────────────────────────────────────
  function msOpenTemplateModal() {
    document.getElementById('msTplModal').classList.add('open');
    msLoadModalPreviews();
  }
  function msCloseTemplateModal() {
    document.getElementById('msTplModal').classList.remove('open');
    msSelectedTplName = null;
    document.getElementById('msApplyTplBtn').disabled = true;
    document.querySelectorAll('.ms-tpl-card').forEach(c => c.classList.remove('selected'));
  }
  function msCloseModalOnOverlay(e) {
    if (e.target === document.getElementById('msTplModal')) msCloseTemplateModal();
  }
  async function msLoadModalPreviews() {
    for (const card of document.querySelectorAll('.ms-tpl-card')) {
      const iframe = card.querySelector('iframe');
      if (!iframe || iframe.dataset.loaded) continue;
      try {
        const data = await (await fetch(msTplRoute(card.dataset.name))).json();
        iframe.srcdoc = data.html;
        iframe.dataset.loaded = '1';
      } catch (_) {}
    }
  }
  function msPreviewTemplate(name, cardEl) {
    document.querySelectorAll('.ms-tpl-card').forEach(c => c.classList.remove('selected'));
    cardEl.classList.add('selected');
    msSelectedTplName = name;
    document.getElementById('msApplyTplBtn').disabled = false;
  }
  async function msApplySelectedTemplate() {
    if (!msSelectedTplName) return;
    await msLoadAndApply(msSelectedTplName);
    document.querySelectorAll('.ms-chip').forEach(c => {
      c.classList.toggle('active', c.dataset.name === msSelectedTplName);
    });
    msCloseTemplateModal();
  }

  // ── LOAD + APPLY ──────────────────────────────────────────────────────
  async function msLoadAndApply(name) {
    msSetStatus('Loading…', '⏳');
    try {
      const res = await fetch(msTplRoute(name));
      if (!res.ok) throw new Error('Not found');
      const data = await res.json();
      msQuill.clipboard.dangerouslyPasteHTML(data.html);
      const subj = document.getElementById('ms-subject');
      // `if (!subj.value.trim() && data.subject) 
        subj.value = data.subject;
      msSetStatus(`"${name}" loaded`, '✅');
    } catch (err) {
      msShowToast('error', '✗', 'Failed to load template: ' + err.message);
      msSetStatus('');
    }
  }
  function msTplRoute(name) {
    return '{{ route("email.template", ":name") }}'.replace(':name', name);
  }

  // ── ATTACHMENTS ───────────────────────────────────────────────────────
  function msHandleAttachments(files) {
    for (const f of files) {
      msAttachedFiles.push(f);
      const chip = Object.assign(document.createElement('div'), { className: 'ms-attach-chip' });
      chip.dataset.name = f.name;
      chip.innerHTML = `📄 <span>${msEsc(f.name)}</span>
        <button onclick="msRemoveAttach('${msEsc(f.name)}',this)" title="Remove">×</button>`;
      document.getElementById('msAttachList').appendChild(chip);
    }
  }
  function msRemoveAttach(name, btn) {
    msAttachedFiles = msAttachedFiles.filter(f => f.name !== name);
    btn.closest('.ms-attach-chip').remove();
  }

  // ── SEND ──────────────────────────────────────────────────────────────
  async function msSendEmail() {
    const to = document.getElementById('ms-to').value.trim();
    const subject = document.getElementById('ms-subject').value.trim();
    if (!to)                       return msShowToast('error', '✗', 'Please enter at least one recipient.');
    if (!subject)                  return msShowToast('error', '✗', 'Subject is required.');
    if (!msQuill.getText().trim()) return msShowToast('error', '✗', 'Message body cannot be empty.');

    const btn = document.getElementById('msSendBtn');
    btn.disabled = true;
    document.getElementById('msSpinner').classList.add('visible');
    msSetStatus('Sending…', '📡');

    const form = new FormData();
    form.append('_token',  msCsrf());
    form.append('to',      to);
    form.append('cc',      document.getElementById('ms-cc').value.trim());
    form.append('bcc',     document.getElementById('ms-bcc').value.trim());
    form.append('subject', subject);
    form.append('body',    msQuill.root.innerHTML);
    msAttachedFiles.forEach(f => form.append('attachments[]', f));

    try {
      const data = await (await fetch('{{ route("email.send") }}', { method: 'POST', body: form })).json();
      if (data.success) {
        msShowToast('success', '✓', 'Email sent successfully!');
        msSetStatus('Sent ✓');
        msResetForm();
      } else {
        msShowToast('error', '✗', data.message || 'Send failed.');
        msSetStatus('');
      }
    } catch (err) {
      msShowToast('error', '✗', 'Network error: ' + err.message);
      msSetStatus('');
    } finally {
      btn.disabled = false;
      document.getElementById('msSpinner').classList.remove('visible');
    }
  }

  // ── DRAFT ─────────────────────────────────────────────────────────────
  function msSaveDraft() {
    localStorage.setItem('ms_draft', JSON.stringify({
      to:      document.getElementById('ms-to').value,
      cc:      document.getElementById('ms-cc').value,
      bcc:     document.getElementById('ms-bcc').value,
      subject: document.getElementById('ms-subject').value,
      body:    msQuill.root.innerHTML,
    }));
    msSetStatus('Draft saved', '💾');
    msShowToast('info', '💾', 'Draft saved.');
  }
  function msLoadDraft() {
    try {
      const d = JSON.parse(localStorage.getItem('ms_draft') || 'null');
      if (!d) return;
      if (document.getElementById('ms-to').value.length == 0)
        document.getElementById('ms-to').value      = d.to      || '';
      document.getElementById('ms-cc').value      = d.cc      || '';
      document.getElementById('ms-bcc').value     = d.bcc     || '';
      document.getElementById('ms-subject').value = d.subject || '';
      if (d.body) msQuill.clipboard.dangerouslyPasteHTML(d.body);
      if (d.cc)  msToggleField('cc');
      if (d.bcc) msToggleField('bcc');
      msSetStatus('Draft restored', '💾');
    } catch (_) {}
  }
  function msDiscardDraft() {
    if (!confirm('Discard this draft?')) return;
    localStorage.removeItem('ms_draft');
    msResetForm();
    msShowToast('info', '🗑', 'Draft discarded.');
  }

  // ── HELPERS ───────────────────────────────────────────────────────────
  function msResetForm() {
    ['ms-to','ms-cc','ms-bcc','ms-subject'].forEach(id => document.getElementById(id).value = '');
    msQuill.setContents([]);
    msAttachedFiles = [];
    document.getElementById('msAttachList').innerHTML = '';
    document.querySelectorAll('.ms-chip').forEach(c => c.classList.remove('active'));
  }
  let _msToastT;
  function msShowToast(type, icon, msg) {
    const t = document.getElementById('msToast');
    t.className = 'ms-toast ' + type;
    document.getElementById('msToastIcon').textContent = icon;
    document.getElementById('msToastMsg').textContent  = msg;
    t.classList.add('show');
    clearTimeout(_msToastT);
    _msToastT = setTimeout(() => t.classList.remove('show'), 3800);
  }
  function msSetStatus(msg, icon = '') {
    document.getElementById('msSaveStatus').textContent = icon ? icon + ' ' + msg : msg;
  }
  function msEsc(s) {
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  // Auto-save every 60 s
  setInterval(() => {
    if (document.getElementById('ms-to').value || msQuill.getText().trim()) msSaveDraft();
  }, 60_000);

  // Restore draft on load
  msLoadDraft();


</script>
@endpush

</x-app-layout>
