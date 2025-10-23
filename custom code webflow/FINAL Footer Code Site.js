<!-- ====================== -->
<!-- Google Tag Manager (noscript) - manter -->
<noscript>
  <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PD6J398"
          height="0" width="0"
          style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- ====================== -->

<!-- ====================== -->
<!-- Submissão especial: abre WhatsApp e depois envia o form -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('form-wp');
    if (!form) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var whatsappUrl = "https://api.whatsapp.com/send?phone=551141718837&text=Ola.%20Quero%20fazer%20uma%20cotacao%20de%20seguro.";
      window.open(whatsappUrl, '_blank');
      form.submit();
    });
  });
</script>
<!-- ====================== -->

<!-- ====================== -->
<!-- Bibliotecas base -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" crossorigin="anonymous"></script>

<!-- SweetAlert2 v11.22.4 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.all.min.js" defer></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.min.css"/>

<!-- RPA JavaScript - Hospedado externamente no mdmidia -->
<script src="https://mdmidia.com.br/webflow-rpa-complete.js" defer></script>

<!-- Configuração RPA - Definir como false para teste -->
<script>
  window.rpaEnabled = false;
  console.log('🎛️ RPA Enabled configurado como:', window.rpaEnabled);
</script>
<!-- ====================== -->

<!-- ====================== -->
<!-- WhatsApp links com GCLID -->
<script>
  function readCookie(name) {
    var n = name + "=", cookie = document.cookie.split(';');
    for (var i = 0; i < cookie.length; i++) {
      var c = cookie[i].trim();
      if (c.indexOf(n) === 0) return c.substring(n.length);
    }
    return null;
  }
  var gclid = readCookie('gclid');

  $(function () {
    ['whatsapplink', 'whatsapplinksucesso', 'whatsappfone1', 'whatsappfone2'].forEach(function (id) {
      var $el = $('#' + id);
      if ($el.length) {
        $el.on('click', function () {
          window.open("https://api.whatsapp.com/send?phone=551132301422&text=Ola.%20Quero%20fazer%20uma%20cotacao%20de%20seguro.%20Codigo%20de%20Desconto=%20" + gclid);
        });
      }
    });
  });
</script>
<!-- ====================== -->

<!-- ====================== -->
<!-- 🎨 Tema SweetAlert2 (Imediato) + centralização + ícone warning azul -->
<style id="swal2-brand-theme">
  :root {
    --brand-primary: #004A8D; /* azul escuro */
    --brand-accent:  #009FE3; /* azul claro  */
    --brand-text:    #004A8D;
  }

  /* Overlay com leve tint azul e sempre centralizado */
  .swal2-container {
    background-color: rgba(0, 74, 141, 0.35) !important;
    z-index: 99999 !important;
  }
  .swal2-popup {
    border-radius: 14px !important;
    box-shadow: 0 16px 50px rgba(0, 74, 141, 0.25) !important;
    padding-top: 22px !important;
  }
  .swal2-title {
    color: var(--brand-text) !important;
    font-weight: 700 !important;
  }
  .swal2-html-container {
    color: #2b3a4a !important;
    line-height: 1.45 !important;
    text-align: center !important;
    white-space: pre-wrap; /* permite \n */
  }

  /* ========= ÍCONES ========= */
  /* WARNING → círculo azul escuro, borda igual, ponto de exclamação branco */
  .swal2-icon.swal2-warning {
    border-color: var(--brand-primary) !important;
    background-color: var(--brand-primary) !important;
    color: #fff !important; /* fallback */
  }
  .swal2-icon.swal2-warning .swal2-icon-content {
    color: #fff !important;  /* ponto de exclamação */
    font-weight: 800 !important;
  }

  /* INFO / SUCCESS (mantêm paleta da marca) */
  .swal2-icon.swal2-info {
    border-color: var(--brand-accent) !important;
    color: var(--brand-accent) !important;
  }
  .swal2-icon.swal2-success {
    border-color: rgba(0,159,227,.35) !important;
    color: var(--brand-accent) !important;
  }

  /* ========= BOTÕES ========= */
  .swal2-actions { gap: 10px !important; }
  .swal2-styled.swal2-cancel {
    color: var(--brand-primary) !important;
    background: #fff !important;
    border: 2px solid var(--brand-primary) !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    min-width: 170px !important;
    padding: 10px 16px !important;
  }
  .swal2-styled.swal2-confirm {
    color: #fff !important;
    background: linear-gradient(180deg, var(--brand-accent) 0%, var(--brand-primary) 100%) !important;
    border: 0 !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    min-width: 170px !important;
    padding: 10px 16px !important;
  }
</style>
<!-- ====================== -->

<!-- ====================== -->
<!-- Validações unificadas: CPF, CEP, PLACA, CELULAR, E-MAIL -->
<script>
/* ========= CONFIG ========= */
const USE_PHONE_API = true;  // usa Apilayer além da regra local
const APILAYER_KEY  = 'dce92fa84152098a3b5b7b8db24debbc';
const SAFETY_BASE   = 'https://optin.safetymails.com/main/safetyoptin/20a7a1c297e39180bd80428ac13c363e882a531f/9bab7f0c2711c5accfb83588c859dc1103844a94/';

// Flag para controlar validação PH3A
const VALIDAR_PH3A = false; // true = consulta API PH3A, false = apenas validação local

/* ========= LOADING ========= */
(function initLoading() {
  if (document.getElementById('si-loading-overlay')) return;
  const style = document.createElement('style');
  style.textContent = `
  #si-loading-overlay{position:fixed;inset:0;background:rgba(0,0,0,.35);display:none;z-index:99998;align-items:center;justify-content:center}
  #si-loading-box{background:#fff;border-radius:12px;padding:18px 22px;box-shadow:0 10px 30px rgba(0,0,0,.2);display:flex;gap:12px;align-items:center;font-family:system-ui}
  .si-spinner{width:20px;height:20px;border:3px solid #e5e7eb;border-top-color:#111827;border-radius:50%;animation:si-spin .8s linear infinite}
  @keyframes si-spin{to{transform:rotate(360deg)}}
  `;
  document.head.appendChild(style);

  const overlay = document.createElement('div');
  overlay.id = 'si-loading-overlay';
  overlay.innerHTML = `<div id="si-loading-box"><div class="si-spinner"></div><div id="si-loading-text">Validando dados…</div></div>`;
  document.body.appendChild(overlay);
})();
let __siLoadingCount = 0;
function showLoading(txt){const o=document.getElementById('si-loading-overlay');const t=document.getElementById('si-loading-text');if(!o||!t)return;if(txt)t.textContent=txt;__siLoadingCount++;o.style.display='flex';}
function hideLoading(){const o=document.getElementById('si-loading-overlay');if(!o)return;__siLoadingCount=Math.max(0,__siLoadingCount-1);if(__siLoadingCount===0)o.style.display='none';}

/* ========= HELPERS ========= */
function onlyDigits(s){return (s||'').replace(/\D+/g,'');}
function toUpperNospace(s){return (s||'').toUpperCase().trim();}
function nativeSubmit($form){var f=$form.get(0);if(!f)return;(typeof f.requestSubmit==='function')?f.requestSubmit():f.submit();}
function setFieldValue(id,val){var $f=$('#'+id+', [name="'+id+'"]');if($f.length){$f.val(val).trigger('input').trigger('change');}}

/* ========= CPF + API PH3A ========= */
function validarCPFFormato(cpf) {
  const cpfLimpo = onlyDigits(cpf);
  return cpfLimpo.length === 11 && !/^(\d)\1{10}$/.test(cpfLimpo);
}

function validarCPFAlgoritmo(cpf) {
  cpf = onlyDigits(cpf);
  if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
  
  let soma = 0, resto = 0;
  for (let i = 1; i <= 9; i++) {
    soma += parseInt(cpf[i-1], 10) * (11 - i);
  }
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  if (resto !== parseInt(cpf[9], 10)) return false;
  
  soma = 0;
  for (let i = 1; i <= 10; i++) {
    soma += parseInt(cpf[i-1], 10) * (12 - i);
  }
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  return resto === parseInt(cpf[10], 10);
}

function extractDataFromPH3A(apiJson) {
  const data = apiJson && apiJson.data;
  if (!data || typeof data !== 'object') {
    return {
      sexo: '',
      dataNascimento: '',
      estadoCivil: ''
    };
  }
  
  // Mapear sexo
  let sexo = '';
  if (data.sexo !== undefined) {
    switch (data.sexo) {
      case 1: sexo = 'Masculino'; break;
      case 2: sexo = 'Feminino'; break;
      default: sexo = ''; break;
    }
  }
  
  // Mapear estado civil
  let estadoCivil = '';
  if (data.estado_civil !== undefined) {
    switch (data.estado_civil) {
      case 0: estadoCivil = 'Solteiro'; break;
      case 1: estadoCivil = 'Casado'; break;
      case 2: estadoCivil = 'Divorciado'; break;
      case 3: estadoCivil = 'Viúvo'; break;
      default: estadoCivil = ''; break;
    }
  }
  
  // Formatar data de nascimento (de ISO para DD/MM/YYYY)
  let dataNascimento = '';
  if (data.data_nascimento) {
    try {
      const date = new Date(data.data_nascimento);
      if (!isNaN(date.getTime())) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        dataNascimento = `${day}/${month}/${year}`;
      }
    } catch (e) {
      dataNascimento = data.data_nascimento;
    }
  }
  
  return {
    sexo: sexo,
    dataNascimento: dataNascimento,
    estadoCivil: estadoCivil
  };
}

function validarCPFApi(cpf) {
  const cpfLimpo = onlyDigits(cpf);
  
  // Primeiro validar formato e algoritmo
  if (!validarCPFFormato(cpfLimpo) || !validarCPFAlgoritmo(cpfLimpo)) {
    return Promise.resolve({
      ok: false, 
      reason: 'formato'
    });
  }
  
  // Consultar API PH3A via proxy
  return fetch('https://mdmidia.com.br/cpf-validate.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      cpf: cpfLimpo
    })
  })
  .then(r => r.json())
  .then(j => {
    const ok = !!j && (j.codigo === 1 || j.success === true);
    return {
      ok, 
      reason: ok ? 'ok' : 'nao_encontrado', 
      parsed: ok ? extractDataFromPH3A(j) : {
        sexo: '',
        dataNascimento: '',
        estadoCivil: ''
      }
    };
  })
  .catch(_ => ({
    ok: false, 
    reason: 'erro_api'
  }));
}

// Função de compatibilidade para código existente
function validarCPF(cpf){
  return validarCPFAlgoritmo(cpf);
}

/* ========= CEP (ViaCEP) ========= */
function preencherEnderecoViaCEP(data){ setFieldValue('CIDADE', data.localidade||''); setFieldValue('ESTADO', data.uf||''); }
function validarCepViaCep(cep){
  cep=onlyDigits(cep);
  if(cep.length!==8) return Promise.resolve({ok:false, reason:'formato'});
  return fetch('https://viacep.com.br/ws/'+cep+'/json/')
    .then(r=>r.json())
    .then(d=>({ok:!d?.erro, reason:d?.erro?'nao_encontrado':'ok', viacep:d}))
    .catch(_=>({ok:false, reason:'erro_api'}));
}

/* ========= PLACA ========= */

// Função para converter para maiúsculas e remover espaços
function toUpperNospace(str) {
  return str.toUpperCase().replace(/\s/g, '');
}

// Função para extrair apenas dígitos
function onlyDigits(str) {
  return str.replace(/[^0-9]/g, '');
}

function validarPlacaFormato(p){
  const placaLimpa = p.toUpperCase().replace(/[^A-Z0-9]/g,'');
  const antigo=/^[A-Z]{3}[0-9]{4}$/;
  const mercosul=/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/;
  return antigo.test(placaLimpa)||mercosul.test(placaLimpa);
}
function extractVehicleFromPlacaFipe(apiJson){
  const r = apiJson && (apiJson.informacoes_veiculo || apiJson);
  if(!r || typeof r !== 'object') return {marcaTxt:'', anoModelo:'', tipoVeiculo:''};
  
  // Extrair dados da API Placa Fipe
  const fabricante = r.marca || '';
  const modelo = r.modelo || '';
  const anoMod = r.ano || r.ano_modelo || '';
  
  // Determinar tipo de veículo baseado no segmento
  let tipoVeiculo = '';
  if(r.segmento) {
    const segmento = r.segmento.toLowerCase();
    if(segmento.includes('moto')) {
      tipoVeiculo = 'moto';
    } else if(segmento.includes('auto')) {
      tipoVeiculo = 'carro';
    } else {
      // Fallback baseado em marcas conhecidas
      const modeloLower = modelo.toLowerCase();
      const marcaLower = fabricante.toLowerCase();
      
      if(marcaLower.includes('honda') || marcaLower.includes('yamaha') || 
         marcaLower.includes('suzuki') || marcaLower.includes('kawasaki') ||
         modeloLower.includes('cg') || modeloLower.includes('cb') || 
         modeloLower.includes('fazer') || modeloLower.includes('ninja')) {
        tipoVeiculo = 'moto';
      } else {
        tipoVeiculo = 'carro';
      }
    }
  } else {
    // Fallback baseado em marcas conhecidas
    const modeloLower = modelo.toLowerCase();
    const marcaLower = fabricante.toLowerCase();
    
    if(marcaLower.includes('honda') || marcaLower.includes('yamaha') || 
       marcaLower.includes('suzuki') || marcaLower.includes('kawasaki') ||
       modeloLower.includes('cg') || modeloLower.includes('cb') || 
       modeloLower.includes('fazer') || modeloLower.includes('ninja')) {
      tipoVeiculo = 'moto';
    } else {
      tipoVeiculo = 'carro';
    }
  }
  
  return { 
    marcaTxt: [fabricante, modelo].filter(Boolean).join(' / '), 
    anoModelo: onlyDigits(String(anoMod)).slice(0,4),
    tipoVeiculo: tipoVeiculo
  };
}

function validarPlacaApi(placa){
  const raw = placa.toUpperCase().replace(/[^A-Z0-9]/g,'');
  if(!validarPlacaFormato(raw)) return Promise.resolve({ok:false, reason:'formato'});
  
  // ✅ URL CORRETA: direto no mdmidia.com.br
  return fetch('https://mdmidia.com.br/placa-validate.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      placa: raw
    })
  })
    .then(r => r.json())
    .then(j => {
      const ok = !!j && (j.codigo === 1 || j.success === true);
      return {
        ok, 
        reason: ok ? 'ok' : 'nao_encontrada', 
        parsed: ok ? extractVehicleFromPlacaFipe(j) : {marcaTxt:'', anoModelo:'', tipoVeiculo:''}
      };
    })
    .catch(_ => ({ok:false, reason:'erro_api'}));
}


// Função de compatibilidade para código existente
function validarPlaca(placa) {
  return validarPlacaApi(placa);
}

/* ========= CELULAR ========= */
/* Máscara jQuery Mask (sem limpar incompletos). Valida apenas no blur do CELULAR. */
function validarCelularLocal(ddd,numero){
  const d=onlyDigits(ddd), n=onlyDigits(numero);
  if(d.length!==2) return {ok:false, reason:'ddd'};
  if(n.length!==9) return {ok:false, reason:'len'};
  if(n[0]!=='9')   return {ok:false, reason:'pattern'};
  return {ok:true, national:d+n};
}
function validarCelularApi(nat){
  return fetch('https://apilayer.net/api/validate?access_key='+APILAYER_KEY+'&country_code=BR&number='+nat)
    .then(r=>r.json())
    .then(j=>({ok:!!j?.valid}))
    .catch(_=>({ok:true})); // falha externa não bloqueia
}
function validarTelefoneAsync($DDD,$CEL){
  const local=validarCelularLocal($DDD.val(),$CEL.val());
  if(!local.ok) return Promise.resolve({ok:false, reason:local.reason});
  if(!USE_PHONE_API) return Promise.resolve({ok:true});
  return validarCelularApi(local.national).then(api=>({ok:api.ok}));
}

/* ========= E-MAIL ========= */
/* Bloqueio: apenas regex. SafetyMails: aviso não bloqueante. */
function validarEmailLocal(v){ return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i.test((v||'').trim()); }

/* ========= MÁSCARAS ========= */
function aplicarMascaraPlaca($i){
  const t={'S':{pattern:/[A-Za-z]/},'0':{pattern:/\d/},'A':{pattern:/[A-Za-z0-9]/}};
  $i.on('input',function(){this.value=this.value.toUpperCase();});
  $i.mask('SSS-0A00',{translation:t, clearIfNotMatch:false});
}

/* ========= BOOT ========= */
$(function () {
  // Campos
  const $CPF   = $('#CPF, [name="CPF"]');
  const $CEP   = $('#CEP, [name="CEP"]');
  const $PLACA = $('#PLACA, [name="PLACA"]');
  const $MARCA = $('#MARCA, [name="MARCA"]');
  const $ANO   = $('#ANO, [name="ANO"]');
  const $DDD   = $('#DDD-CELULAR, [name="DDD-CELULAR"]');
  const $CEL   = $('#CELULAR, [name="CELULAR"]');
  const $EMAIL = $('#email, [name="email"], #EMAIL, [name="EMAIL"]');

  // Máscaras
  if ($CPF.length)   $CPF.mask('000.000.000-00');
  if ($CEP.length)   $CEP.mask('00000-000');
  if ($PLACA.length) aplicarMascaraPlaca($PLACA);
  if ($DDD.length)   $DDD.off('.siPhone').mask('00', { clearIfNotMatch:false });
  if ($CEL.length)   $CEL.off('.siPhone').mask('00000-0000', { clearIfNotMatch:false });

  // ============ Helpers de Alert (SweetAlert2) ============
  function saWarnConfirmCancel(opts) {
    return Swal.fire(Object.assign({
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Corrigir',
      cancelButtonText: 'Não',
      reverseButtons: true,
      allowOutsideClick: false,
      allowEscapeKey: true
    }, opts));
  }
  function saInfoConfirmCancel(opts) {
    return Swal.fire(Object.assign({
      icon: 'info',
      showCancelButton: true,
      confirmButtonText: 'Prosseguir assim mesmo',
      cancelButtonText: 'Corrigir',
      reverseButtons: true,
      allowOutsideClick: false,
      allowEscapeKey: true
    }, opts));
  }

  // CPF → change (com/sem API PH3A)
  $CPF.on('change', function(){
    const cpfValue = $(this).val();
    
    // Validação local primeiro
    if (!validarCPFAlgoritmo(cpfValue)) {
      saWarnConfirmCancel({
        title: 'CPF inválido',
        html: 'Deseja corrigir?'
      }).then(r => { 
        if (r.isConfirmed) $CPF.focus(); 
      });
      return;
    }
    
    // Se flag VALIDAR_PH3A estiver desabilitada, apenas validar formato
    if (!VALIDAR_PH3A) {
      // CPF válido, mas sem consulta à API - limpar campos para preenchimento manual
      setFieldValue('SEXO', '');
      setFieldValue('DATA-DE-NASCIMENTO', '');
      setFieldValue('ESTADO-CIVIL', '');
      return;
    }
    
    // Se CPF válido e flag ativa, consultar API PH3A
    showLoading('Consultando dados do CPF…');
    validarCPFApi(cpfValue).then(res => {
      hideLoading();
      
      if (res.ok && res.parsed) {
        // Preencher campos automaticamente
        if (res.parsed.sexo) setFieldValue('SEXO', res.parsed.sexo);
        if (res.parsed.dataNascimento) setFieldValue('DATA-DE-NASCIMENTO', res.parsed.dataNascimento);
        if (res.parsed.estadoCivil) setFieldValue('ESTADO-CIVIL', res.parsed.estadoCivil);
      } else if (res.reason === 'nao_encontrado') {
        // CPF válido mas não encontrado na base
        saInfoConfirmCancel({
          title: 'CPF não encontrado',
          html: 'O CPF é válido, mas não foi encontrado na nossa base de dados.<br><br>Deseja preencher os dados manualmente?'
        }).then(r => {
          if (r.isConfirmed) {
            // Limpar campos e permitir preenchimento manual
            setFieldValue('SEXO', '');
            setFieldValue('DATA-DE-NASCIMENTO', '');
            setFieldValue('ESTADO-CIVIL', '');
          }
        });
      }
    }).catch(_ => {
      hideLoading();
      // Em caso de erro na API, não bloquear o usuário
      console.log('Erro na consulta da API PH3A');
    });
  });

  // CEP → change (ViaCEP)
  $CEP.on('change', function(){
    const val = $(this).val();
    showLoading('Validando CEP…');
    validarCepViaCep(val).then(res=>{
      hideLoading();
      if (!res.ok){
        saWarnConfirmCancel({
          title: 'CEP inválido',
          html: 'Deseja corrigir?'
        }).then(r=>{ if (r.isConfirmed) $CEP.focus(); });
      } else if (res.viacep){
        preencherEnderecoViaCEP(res.viacep);
      }
    }).catch(_=>hideLoading());
  });

  // PLACA → change (preenche MARCA/ANO/TIPO se ok)
  $PLACA.on('change', function(){
    showLoading('Validando placa…');
    validarPlacaApi($(this).val()).then(res=>{
      hideLoading();
      if (!res.ok){
        saWarnConfirmCancel({
          title: 'Placa inválida',
          html: 'Deseja corrigir?'
        }).then(r=>{ if (r.isConfirmed) $PLACA.focus(); });
        setFieldValue('MARCA',''); setFieldValue('ANO',''); setFieldValue('TIPO-DE-VEICULO','');
      } else {
        if (res.parsed?.marcaTxt) setFieldValue('MARCA', res.parsed.marcaTxt);
        if (res.parsed?.anoModelo) setFieldValue('ANO',   res.parsed.anoModelo);
        if (res.parsed?.tipoVeiculo) setFieldValue('TIPO-DE-VEICULO', res.parsed.tipoVeiculo);
      }
    }).catch(_=>hideLoading());
  });

  // CELULAR → valida SÓ no BLUR do CELULAR
  $DDD.off('change'); $CEL.off('change'); // remove handlers antigos
  $CEL.on('blur.siPhone', function(){
    const dddDigits = onlyDigits($DDD.val()).length;
    const celDigits = onlyDigits($CEL.val()).length;

    // ✅ CORREÇÃO: Validar DDD e CELULAR separadamente
    if (dddDigits !== 2) {
      saWarnConfirmCancel({
        title: 'DDD inválido',
        html: 'O DDD precisa ter 2 dígitos.<br><br>Deseja corrigir?'
      }).then(r=>{ if (r.isConfirmed) $DDD.focus(); });
      return;
    }
    
    if (celDigits > 0 && celDigits < 9) {
      saWarnConfirmCancel({
        title: 'Celular incompleto',
        html: 'O celular precisa ter 9 dígitos.<br><br>Deseja corrigir?'
      }).then(r=>{ if (r.isConfirmed) $CEL.focus(); });
      return;
    }

    // Se DDD=2 e celular=9 → valida via API
    if (dddDigits === 2 && celDigits === 9){
      showLoading('Validando celular…');
      validarTelefoneAsync($DDD,$CEL).then(res=>{
        hideLoading();
        if (!res.ok){
          const numero = `${($DDD.val()||'').trim()}-${($CEL.val()||'').trim()}`;
          saWarnConfirmCancel({
            title: 'Celular inválido',
            html: `Parece que o celular informado<br><br><b>${numero}</b><br><br>não é válido.<br><br>Deseja corrigir?`
          }).then(r=>{ if (r.isConfirmed) $CEL.focus(); });
        }
      }).catch(_=>hideLoading());
    }
    // Se DDD incompleto ou celular vazio → não valida agora (submit cuida)
  });

  // E-MAIL → change (regex bloqueia; SafetyMails só avisa)
  $EMAIL.on('change.siMail', function(){
    const v = ($(this).val()||'').trim();
    if (!v) return;
    if (!validarEmailLocal(v)){
      saWarnConfirmCancel({
        title: 'E-mail inválido',
        html: `O e-mail informado:<br><br><b>${v}</b><br><br>não parece válido.<br><br>Deseja corrigir?`,
        cancelButtonText: 'Não Corrigir',
        confirmButtonText: 'Corrigir'
      }).then(r=>{ if (r.isConfirmed) $EMAIL.focus(); });
      return;
    }
    // Aviso opcional via SafetyMails (não bloqueia)
    fetch(SAFETY_BASE + btoa(v))
      .then(r=>r.json())
      .then(resp=>{
        if (resp && resp.StatusEmail && resp.StatusEmail !== 'VALIDO'){
          saWarnConfirmCancel({
            title: 'Atenção',
            html: `O e-mail informado:<br><br><b>${v}</b><br><br>pode não ser válido segundo verificador externo.<br><br>Deseja corrigir?`,
            cancelButtonText: 'Manter',
            confirmButtonText: 'Corrigir'
          }).then(r=>{ if (r.isConfirmed) $EMAIL.focus(); });
        }
      })
      .catch(()=>{ /* silêncio em erro externo */ });
  });

        // SUBMIT — revalida tudo e oferece Corrigir / Prosseguir
        $('form').each(function(){
          const $form=$(this);
          $form.on('submit', function(ev){
            // ✅ INTERCEPTAÇÃO CONDICIONAL RPA
            if (window.rpaEnabled === false) {
              console.log('RPA desabilitado - redirect para sucesso');
              window.location.href = 'https://www.segurosimediato.com.br/sucesso';
              return false;
            }
            
            if (window.rpaEnabled === true) {
              console.log('RPA habilitado - deixando JavaScript externo processar');
              return; // Não intercepta - deixa para RPA
            }
      if ($form.data('validated-ok') === true) { $form.removeData('validated-ok'); return true; }
      if ($form.data('skip-validate') === true){ $form.removeData('skip-validate');  return true; }

      ev.preventDefault();
      showLoading('Validando seus dados…');

      Promise.all([
        $CPF.length ? (VALIDAR_PH3A ? validarCPFApi($CPF.val()) : Promise.resolve({ok: validarCPFAlgoritmo($CPF.val())})) : Promise.resolve({ok: true}),
        $CEP.length   ? validarCepViaCep($CEP.val())  : Promise.resolve({ok:true}),
        $PLACA.length ? validarPlacaApi($PLACA.val()) : Promise.resolve({ok:true}),
        // TELEFONE no submit — considera incompleto como inválido
        ($DDD.length && $CEL.length)
          ? (function(){
              const d = onlyDigits($DDD.val()).length;
              const n = onlyDigits($CEL.val()).length;
              if (d === 2 && n === 9) return validarTelefoneAsync($DDD,$CEL);    // completo → valida API
              if (d === 2 && n > 0 && n < 9) return Promise.resolve({ok:false});  // incompleto → inválido
              return Promise.resolve({ok:false}); // ddd incompleto ou vazio → inválido
            })()
          : Promise.resolve({ok:true}),
        // E-mail: regex (bloqueante)
        $EMAIL.length ? Promise.resolve({ok: validarEmailLocal(($EMAIL.val()||'').trim())}) : Promise.resolve({ok:true})
      ])
      .then(([cpfRes, cepRes, placaRes, telRes, mailRes])=>{
        hideLoading();

        // autopreenche MARCA/ANO/TIPO de novo se validou placa
        if (placaRes.ok && placaRes.parsed){
          if (placaRes.parsed.marcaTxt) setFieldValue('MARCA', placaRes.parsed.marcaTxt);
          if (placaRes.parsed.anoModelo) setFieldValue('ANO',   placaRes.parsed.anoModelo);
          if (placaRes.parsed.tipoVeiculo) setFieldValue('TIPO-DE-VEICULO', placaRes.parsed.tipoVeiculo);
        }

        // autopreenche SEXO/DATA/ESTADO-CIVIL se validou CPF com API
        if (cpfRes.ok && cpfRes.parsed && VALIDAR_PH3A) {
          if (cpfRes.parsed.sexo) setFieldValue('SEXO', cpfRes.parsed.sexo);
          if (cpfRes.parsed.dataNascimento) setFieldValue('DATA-DE-NASCIMENTO', cpfRes.parsed.dataNascimento);
          if (cpfRes.parsed.estadoCivil) setFieldValue('ESTADO-CIVIL', cpfRes.parsed.estadoCivil);
        }

        const invalido = (!cpfRes.ok) || (!cepRes.ok) || (!placaRes.ok) || (!telRes.ok) || (!mailRes.ok);

        if (!invalido){
          // ❌ ANTIGO: $form.data('validated-ok', true); nativeSubmit($form);
          // ✅ NOVO: REDIRECT MANUAL PARA SUCESSO
          window.location.href = 'https://www.segurosimediato.com.br/sucesso';
        } else {
          let linhas = "";
          if (!cpfRes.ok)       linhas += "• CPF inválido\n";
          if (!cepRes.ok)   linhas += "• CEP inválido\n";
          if (!placaRes.ok) linhas += "• Placa inválida\n";
          if (!telRes.ok)   linhas += "• Celular inválido\n";
          if (!mailRes.ok)  linhas += "• E-mail inválido\n";

          Swal.fire({
            icon: 'info',
            title: 'Atenção!',
            html:
              "⚠️ Os campos CPF, CEP, PLACA, CELULAR e E-MAIL corretamente preenchidos são necessários para efetuar o cálculo do seguro.\n\n" +
              "Campos com problema:\n\n" + linhas + "\n" +
              "Caso decida prosseguir assim mesmo, um especialista entrará em contato para coletar esses dados.",
            showCancelButton: true,
            confirmButtonText: 'Prosseguir assim mesmo',
            cancelButtonText: 'Corrigir',
            reverseButtons: true,
            allowOutsideClick: false,
            allowEscapeKey: true
          }).then(r=>{
            if (r.isConfirmed){
              // ❌ ANTIGO: $form.data('skip-validate', true); nativeSubmit($form);
              // ✅ NOVO: REDIRECT MANUAL PARA SUCESSO
              window.location.href = 'https://www.segurosimediato.com.br/sucesso';
            } else {
              if (!cpfRes.ok && $CPF.length)        { $CPF.focus(); return; }
              if (!cepRes.ok && $CEP.length)    { $CEP.focus(); return; }
              if (!placaRes.ok && $PLACA.length){ $PLACA.focus(); return; }
              if (!telRes.ok && ($DDD.length && $CEL.length)) { $CEL.focus(); return; }
              if (!mailRes.ok && $EMAIL.length) { $EMAIL.focus(); return; }
            }
          });
        }
      })
      .catch(_=>{
        hideLoading();
        Swal.fire({
          icon: 'info',
          title: 'Não foi possível validar agora',
          html:  'Deseja prosseguir assim mesmo?',
          showCancelButton: true,
          confirmButtonText: 'Prosseguir assim mesmo',
          cancelButtonText: 'Corrigir',
          reverseButtons: true,
          allowOutsideClick: false,
          allowEscapeKey: true
        }).then(r=>{
          if (r.isConfirmed) { 
            // ❌ ANTIGO: $form.data('skip-validate', true); nativeSubmit($form);
            // ✅ NOVO: REDIRECT MANUAL PARA SUCESSO
            window.location.href = 'https://www.segurosimediato.com.br/sucesso';
          }
        });
      });
    });
  });
});
</script>
<!-- ====================== -->

<script>
window.Webflow ||= [];
window.Webflow.push(() => {
  const LIST = document.querySelector('#Equipes-list');        // ID da Collection List (Equipes)
  const OUT  = document.getElementById('qtde_colaboradores');  // seu elemento de texto

  const isVisible = (el) => {
    const st = getComputedStyle(el);
    return el.offsetParent !== null && st.display !== 'none' && st.visibility !== 'hidden' && st.opacity !== '0';
  };

  const recalc = () => {
    const n = LIST ? [...LIST.querySelectorAll('.w-dyn-item')].filter(isVisible).length : 0;
    if (OUT) OUT.textContent = String(n);
  };

  recalc(); // na carga

  // Atualiza em mudanças (filtros/paginação/dinâmicas)
  if (LIST) new MutationObserver(recalc).observe(LIST, {
    childList: true, subtree: true, attributes: true, attributeFilter: ['style','class']
  });
  document.addEventListener('fs-cmsfilter-update', recalc);       // Finsweet
  document.addEventListener('jetboost:filter:applied', recalc);    // Jetboost
  document.addEventListener('jetboost:pagination:loaded', recalc); // Jetboost
});
</script>