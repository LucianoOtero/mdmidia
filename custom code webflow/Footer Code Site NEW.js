<!-- ====================== -->
<!-- ======================================== -->
<!-- FOOTER CODE SITE NEW - VERSÃO RPA V6.13.0 -->
<!-- ======================================== -->
<!-- 
  ALTERAÇÕES PARA PROJETO RPA:
  
  ✅ MANTIDO:
  - Validações individuais em tempo real (CPF, CEP, Placa, Celular, Email)
  - Auto-preenchimento de campos (MARCA/ANO/TIPO, CIDADE/ESTADO, SEXO/DATA/ESTADO-CIVIL)
  - SweetAlert individual para cada campo
  - Bibliotecas jQuery e jQuery.mask
  - Funções GCLID e WhatsApp
  - Google Tag Manager (noscript)
  - Contador de Equipes
  
  ❌ COMENTADO:
  - Validação de submit (implementada no RPA JavaScript)
  
  ✅ NOVO:
  - RPA JavaScript injetado no início para carregar SweetAlert2 primeiro
  - SweetAlert2 v11.22.4 descomentado para validações individuais funcionarem
  
  DATA: 18/10/2025
  VERSÃO: V6.13.0
  PROJETO: Validação completa de formulário integrada ao RPA
-->
<!-- ====================== -->
<!-- Google Tag Manager (noscript) - manter -->
<noscript>
  <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PD6J398"
          height="0" width="0"
          style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- ====================== -->

<!-- SweetAlert2 v11.22.4 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.all.min.js" defer></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.min.css"/>


/**
 * INJEÇÃO COMPLETA WEBFLOW - IMEDIATO SEGUROS V6.12.0
 * Arquivo único para injeção no Webflow
 * 
 * Contém:
 * - CSS completo (inline)
 * - HTML do modal (dinâmico)
 * - JavaScript completo
 * - Integração RPA
 * - SpinnerTimer integrado com ciclo de vida do RPA
 * 
 * USO: Copiar todo este código para o Custom Code do Webflow
 */

(function() {
  'use strict';
  
  // ========================================
  // 1. CSS COMPLETO (INLINE)
  // ========================================
  
  const cssStyles = `
      /* IDENTIDADE VISUAL IMEDIATO SEGUROS V6.2.2 */
      
      /* Importar fonte Titillium Web */
      @import url('https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600;700&display=swap');
      
      /* Variáveis CSS com cores da Imediato */
      :root {
          --imediato-dark-blue: #003366;
          --imediato-light-blue: #0099CC;
          --imediato-white: #FFFFFF;
          --imediato-gray: #F8F9FA;
          --imediato-text: #333333;
          --imediato-text-light: #666666;
          --imediato-border: #E0E0E0;
          --imediato-shadow: rgba(0, 51, 102, 0.1);
          --imediato-shadow-hover: rgba(0, 51, 102, 0.2);
          
          /* Font sizes */
          --font-size-xs: 0.75rem;
          --font-size-sm: 0.875rem;
          --font-size-base: 1rem;
          --font-size-lg: 1.125rem;
          --font-size-xl: 1.25rem;
          --font-size-2xl: 1.5rem;
          --font-size-3xl: 1.875rem;
          --font-size-4xl: 2.25rem;
      }
      
      /* MODAL DE PROGRESSO V6.2.2 - IMEDIATO SEGUROS */
      
      /* Aplicar fonte Titillium Web em todos os elementos do modal */
      #rpaModal * {
          font-family: 'Titillium Web', sans-serif !important;
          font-size: var(--font-size-base) !important;
      }
      
      /* Tamanhos específicos para elementos do modal */
      #rpaModal h1 {
          font-size: var(--font-size-2xl);
          font-weight: 600;
      }
      
      #rpaModal h3 {
          font-size: var(--font-size-lg) !important;
          font-weight: 600 !important;
      }
      
      #rpaModal .progress-text {
          font-size: var(--font-size-xl) !important;
          font-weight: 700 !important;
      }
      
      #rpaModal .current-phase {
          font-size: var(--font-size-lg) !important;
          font-weight: 500 !important;
      }
      
      #rpaModal .sub-phase {
          font-size: var(--font-size-sm) !important;
          font-weight: 400 !important;
      }
      
      #rpaModal .stage-info {
          font-size: var(--font-size-sm) !important;
          font-weight: 500 !important;
      }
      
      #rpaModal .value {
          font-size: var(--font-size-2xl) !important;
          font-weight: 700 !important;
      }
      
      #rpaModal .card-subtitle {
          font-size: var(--font-size-sm) !important;
          font-weight: 400 !important;
      }
      
      #rpaModal .card-features li {
          font-size: var(--font-size-sm) !important;
          font-weight: 400 !important;
          padding: 0.3rem 0 !important;
      }
      
      #rpaModal .btn {
          font-size: var(--font-size-base) !important;
          font-weight: 500 !important;
      }
      
      /* Reset específico para modal dinâmico - SEM remover todos os estilos */
      #rpaModal {
          position: fixed !important;
          top: 10vh !important;
          left: 10vw !important;
          width: 80vw !important;
          height: 80vh !important;
          background: rgba(255, 255, 255, 0.02) !important;
          z-index: 999999 !important;
          display: flex !important;
          flex-direction: column !important;
          margin: 0 !important;
          padding: 0 !important;
          border: none !important;
          box-shadow: 
              0 30px 60px rgba(0, 0, 0, 0.2),
              0 0 0 1px rgba(255, 255, 255, 0.05) !important;
          backdrop-filter: blur(20px) !important;
          overflow: hidden !important;
          box-sizing: border-box !important;
          isolation: isolate !important;
          font-family: 'Titillium Web', sans-serif !important;
      }
      
      /* Estilos específicos para elementos do modal */
      #rpaModal * {
          box-sizing: border-box !important;
      }
      
      /* Garantir que o conteúdo do modal tenha estilos corretos */
      #rpaModal .modal-content {
          background: var(--imediato-white) !important;
          border-radius: 15px !important;
          padding: 1rem !important;
          margin: 1rem !important;
          box-shadow: 0 10px 30px var(--imediato-shadow) !important;
          flex: 1 !important;
          overflow-y: auto !important;
          font-family: 'Titillium Web', sans-serif !important;
      }
      
      /* Garantir que os cards tenham estilos corretos */
      #rpaModal .result-card {
          background: var(--imediato-white) !important;
          border-radius: 12px !important;
          padding: 1rem !important;
          box-shadow: 0 4px 15px var(--imediato-shadow) !important;
          border: 2px solid var(--imediato-border) !important;
          font-family: 'Titillium Web', sans-serif !important;
      }
      
      /* Garantir que os headers dos cards tenham estilos corretos */
      #rpaModal .card-header {
          display: flex !important;
          align-items: center !important;
          gap: 1rem !important;
          justify-content: space-between !important;
          margin-bottom: 0.75rem !important;
      }
      
      /* Garantir que os títulos tenham estilos corretos */
      #rpaModal .card-title h3 {
          font-size: var(--font-size-2xl) !important;
          font-weight: 600 !important;
          color: var(--imediato-dark-blue) !important;
          margin: 0 !important;
          font-family: 'Titillium Web', sans-serif !important;
      }
      
      /* Garantir que o título ocupe espaço disponível */
      #rpaModal .card-title {
          flex: 1 !important;
      }
      
      /* Estilos para o valor inline no header */
      #rpaModal .card-value-inline {
          text-align: right !important;
          margin-left: auto !important;
      }
      
      #rpaModal .card-value-inline .value {
          font-size: var(--font-size-2xl) !important;
          font-weight: 700 !important;
          color: var(--imediato-dark-blue) !important;
          margin: 0 !important;
      }
      
      #rpaModal .card-subtitle {
          font-size: var(--font-size-sm) !important;
          color: var(--imediato-text-light) !important;
          margin: 0.25rem 0 0 0 !important;
          font-family: 'Titillium Web', sans-serif !important;
      }
      
      /* Garantir que os valores tenham estilos corretos */
      #rpaModal .value {
          font-size: var(--font-size-2xl) !important;
          font-weight: 700 !important;
          color: var(--imediato-dark-blue) !important;
          text-align: center !important;
          font-family: 'Titillium Web', sans-serif !important;
      }
      
      /* Garantir que o container de resultados tenha layout correto */
      #rpaModal .results-container {
          display: grid !important;
          grid-template-columns: 1fr 1fr !important;
          gap: 1.5rem !important;
      }
      
      @media (max-width: 768px) {
          #rpaModal .results-container {
              grid-template-columns: 1fr !important;
              gap: 1rem !important;
          }
          
          /* Layout vertical para mobile */
          #rpaModal .card-header {
              flex-direction: column !important;
              align-items: flex-start !important;
              gap: 0.5rem !important;
          }
          
          #rpaModal .card-value-inline {
              text-align: left !important;
              margin-left: 0 !important;
              margin-top: 0.5rem !important;
          }
      }
      
      /* Garantir que os botões tenham estilos corretos */
      #rpaModal .btn {
          background: linear-gradient(135deg, var(--imediato-dark-blue) 0%, var(--imediato-light-blue) 100%) !important;
          color: var(--imediato-white) !important;
          border: none !important;
          padding: 0.75rem 1.5rem !important;
          border-radius: 8px !important;
          font-size: var(--font-size-base) !important;
          font-weight: 500 !important;
          font-family: 'Titillium Web', sans-serif !important;
          cursor: pointer !important;
          transition: all 0.3s ease !important;
          text-decoration: none !important;
          display: inline-flex !important;
          align-items: center !important;
          justify-content: center !important;
          gap: 0.5rem !important;
      }
      
      #rpaModal .btn:hover {
          transform: translateY(-2px) !important;
          box-shadow: 0 4px 15px var(--imediato-shadow-hover) !important;
      }
      
      /* Responsividade do modal */
      @media (max-width: 768px) {
          #rpaModal {
              top: 5vh !important;
              left: 5vw !important;
              width: 90vw !important;
              height: 90vh !important;
          }
      }
      
      @media (max-width: 480px) {
          #rpaModal {
              top: 2vh !important;
              left: 2vw !important;
              width: 96vw !important;
              height: 96vh !important;
          }
          
          #rpaModal .progress-header .company-logo {
              height: 40px;
              width: auto;
          }
      }
      
      /* Garantir que os ícones Font Awesome funcionem */
      #rpaModal i.fas,
      #rpaModal i.far,
      #rpaModal i.fab {
          font-family: "Font Awesome 6 Free" !important;
          font-weight: 900 !important;
          font-style: normal !important;
          font-variant: normal !important;
          text-rendering: auto !important;
          -webkit-font-smoothing: antialiased !important;
          -moz-osx-font-smoothing: grayscale !important;
          display: inline-block !important;
      }
      
      /* Garantir que o modal seja sempre visível */
      #rpaModal.show {
          opacity: 1 !important;
          visibility: visible !important;
      }
      
      /* Restaurar estilos específicos após reset */
      #rpaModal .modal-progress-bar {
          background: var(--imediato-white);
          position: sticky;
          top: 0;
          z-index: 10001;
      }
      
      #rpaModal .progress-header {
          display: flex;
          flex-direction: column;
          align-items: center;
          padding: 1.2rem 2rem;
          background-color: #f2f5f8;
          background-image: url(https://cdn.prod.website-files.com/59eb807f9d16950001e202af/68ad0b4d507fa7c358ff42e2_header-grid-nodes-12-standard.svg);
          background-position: 0 0;
          background-size: auto;
          background-repeat: no-repeat;
          color: var(--imediato-dark-blue);
          text-align: center;
      }
      
      #rpaModal .modal-content {
          flex: 1;
          background: linear-gradient(135deg, var(--imediato-gray), var(--imediato-white));
          overflow-y: auto;
          padding: 1rem;
      }
      
      #rpaModal .progress-header .logo-container {
          margin-bottom: 1.5rem;
      }
      
      #rpaModal .progress-header h1 {
          color: var(--imediato-dark-blue);
          font-size: 1.8rem;
          font-weight: 600;
          margin: 0 0 1rem 0;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 0.5rem;
      }
      
      #rpaModal .progress-header h1 i {
          color: var(--imediato-dark-blue);
      }
      
      #rpaModal .progress-header .company-logo {
          height: 60px;
          width: auto;
          filter: none;
          object-fit: contain;
      }
      
      #rpaModal .progress-info {
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 1.5rem;
          margin-top: 0.5rem;
      }
      
      .progress-text {
          font-size: 1.8rem;
          font-weight: 700;
          color: var(--imediato-dark-blue);
          min-width: 70px;
          text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }
      
      .current-phase {
          color: var(--imediato-dark-blue);
          font-size: 1.1rem;
          font-weight: 500;
      }
      
      .sub-phase {
          color: var(--imediato-dark-blue);
          font-size: 0.95rem;
          font-weight: 400;
          margin-top: 0.25rem;
          font-style: italic;
          opacity: 0.8;
      }
      
      .progress-stages {
          color: var(--imediato-dark-blue);
          font-size: 1rem;
          font-weight: 400;
          text-align: center;
          margin-top: 0.5rem;
          opacity: 0.9;
      }
      
      .progress-bar-wrapper {
          padding: 0 2rem 0;
          background: var(--imediato-white);
      }
      
      .progress-bar-container {
          width: 100%;
          height: 8px;
          background: var(--imediato-border);
          border-radius: 4px;
          overflow: hidden;
          position: relative;
      }
      
      .progress-bar-fill {
          height: 100%;
          background: linear-gradient(90deg, var(--imediato-light-blue), var(--imediato-dark-blue));
          border-radius: 4px;
          transition: width 0.3s ease;
          position: relative;
      }
      
      .progress-bar-fill::after {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
          animation: shimmer 2s infinite;
      }
      
      @keyframes shimmer {
          0% { transform: translateX(-100%); }
          100% { transform: translateX(100%); }
      }
      
      /* Container dos 2 Divs */
      #rpaModal .results-container {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 2rem;
      }
      
      #rpaModal .result-card {
          background: var(--imediato-white);
          border-radius: 15px;
          padding: 1rem;
          box-shadow: 0 8px 25px var(--imediato-shadow);
          border: 2px solid var(--imediato-border);
          transition: all 0.3s ease;
          position: relative;
          overflow: hidden;
      }
      
      #rpaModal .result-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 15px 35px var(--imediato-shadow-hover);
      }
      
      #rpaModal .result-card.recommended {
          border-color: var(--imediato-light-blue);
          background: linear-gradient(135deg, var(--imediato-white), #f0f8ff);
      }
      
      #rpaModal .result-card.alternative {
          border-color: var(--imediato-border);
          background: linear-gradient(135deg, var(--imediato-white), var(--imediato-gray));
      }
      
      .card-header {
          display: flex;
          align-items: center;
          gap: 1rem;
          margin-bottom: 1.5rem;
      }
      
      .card-icon {
          width: 60px;
          height: 60px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 1.5rem !important;
          background: var(--imediato-gray);
          flex-shrink: 0;
          min-width: 60px;
          min-height: 60px;
      }
      
      .card-icon i {
          font-size: 1.5rem !important;
          color: inherit !important;
          display: inline-block !important;
          font-family: "Font Awesome 6 Free" !important;
          font-weight: 900 !important;
      }
      
      /* Forçar estilos dos ícones Font Awesome */
      #rpaModal .card-icon i.fas {
          font-family: "Font Awesome 6 Free" !important;
          font-weight: 900 !important;
          font-style: normal !important;
          font-variant: normal !important;
          text-rendering: auto !important;
          -webkit-font-smoothing: antialiased !important;
          -moz-osx-font-smoothing: grayscale !important;
      }
      
      .result-card.recommended .card-icon {
          background: var(--imediato-light-blue);
          color: var(--imediato-white) !important;
      }
      
      .result-card.recommended .card-icon i {
          color: var(--imediato-white) !important;
      }
      
      .result-card.alternative .card-icon {
          background: var(--imediato-gray);
          color: var(--imediato-dark-blue) !important;
      }
      
      .result-card.alternative .card-icon i {
          color: var(--imediato-dark-blue) !important;
      }
      
      /* Ícones no header */
      #rpaModal h1 i {
          font-size: 1.5rem !important;
          margin-right: 0.5rem !important;
          color: var(--imediato-light-blue) !important;
          display: inline-block !important;
      }
      
      /* Ícones nos botões */
      #rpaModal .btn i {
          font-size: 1rem !important;
          margin-right: 0.5rem !important;
          display: inline-block !important;
      }
      
      /* Botão X no canto superior direito do modal */
      #rpaModal .modal-close-btn {
          position: absolute !important;
          top: 0.5rem !important;
          right: 0.5rem !important;
          width: 40px !important;
          height: 40px !important;
          background: var(--imediato-dark-blue) !important;
          border: none !important;
          border-radius: 8px !important;
          color: var(--imediato-white) !important;
          font-size: 16px !important;
          cursor: pointer !important;
          display: flex !important;
          align-items: center !important;
          justify-content: center !important;
          z-index: 10002 !important;
          transition: all 0.3s ease !important;
          box-shadow: 0 2px 8px rgba(0, 51, 102, 0.3) !important;
      }
      
      #rpaModal .modal-close-btn:hover {
          background: var(--imediato-light-blue) !important;
          transform: scale(1.1) !important;
          box-shadow: 0 4px 12px rgba(0, 51, 102, 0.4) !important;
      }
      
      #rpaModal .modal-close-btn:active {
          transform: scale(0.95) !important;
      }
      
      /* Ícones nas features */
      #rpaModal .card-features i {
          font-size: 0.875rem !important;
          margin-right: 0.5rem !important;
          color: var(--imediato-light-blue) !important;
          display: inline-block !important;
      }
      
      .card-title h3 {
          color: var(--imediato-dark-blue);
          font-size: 1.3rem;
          font-weight: 600;
          margin: 0 0 0.5rem 0;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          max-width: 100%;
      }
      
      .card-subtitle {
          color: var(--imediato-text-light);
          font-size: 0.9rem;
          font-weight: 400;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          max-width: 100%;
      }
      
      .card-value {
          text-align: center;
          margin: 1.5rem 0;
      }
      
      .value {
          font-size: 2.5rem;
          font-weight: 700;
          color: var(--imediato-dark-blue);
          margin: 0;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          max-width: 100%;
      }
      
      .card-features {
          list-style: none;
          padding: 0;
          margin: 1.2rem 0 0 0;
      }
      
      .card-features li {
          padding: 0.3rem 0;
          color: var(--imediato-text);
          font-size: 0.9rem;
          border-bottom: 1px solid var(--imediato-border);
          display: flex;
          justify-content: space-between;
          align-items: center;
      }
      
      .card-features li:last-child {
          border-bottom: none;
      }
      
      .card-features li i {
          color: var(--imediato-light-blue);
          margin-right: 0.5rem;
          width: 16px;
      }
      
      .feature-value {
          font-weight: 600;
          color: var(--imediato-dark-blue);
          text-align: right;
          flex-shrink: 0;
      }
      
      .text-success {
          color: #28a745 !important;
      }
      
      .text-danger {
          color: #dc3545 !important;
      }
      
      .action-buttons {
          display: flex;
          gap: 1rem;
          justify-content: center;
          margin-top: 2rem;
          padding: 0 2rem 2rem;
      }
      
      .btn {
          padding: 1rem 2rem;
          border: none;
          border-radius: 8px;
          font-size: 1rem;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
          text-decoration: none;
          display: inline-flex;
          align-items: center;
          gap: 0.5rem;
      }
      
      .btn-primary {
          background: linear-gradient(135deg, var(--imediato-light-blue), var(--imediato-dark-blue));
          color: var(--imediato-white);
      }
      
      .btn-primary:hover {
          transform: translateY(-2px);
          box-shadow: 0 8px 20px var(--imediato-shadow-hover);
      }
      
      .btn-secondary {
          background: var(--imediato-gray);
          color: var(--imediato-dark-blue);
          border: 2px solid var(--imediato-border);
      }
      
      .btn-secondary:hover {
          background: var(--imediato-border);
          transform: translateY(-2px);
      }
      
      /* SPINNER TIMER CONTAINER */
      .spinner-timer-container {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          padding: 2rem;
          background: transparent;
          border: none;
          /* ✅ CORREÇÃO: Posicionamento centralizado no modal */
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          z-index: 1000;
      }
      
      .spinner-container {
          position: relative;
          width: 240px;
          height: 240px;
          margin-bottom: 1rem;
      }
      
      /* SpinKit Modelo 8 - Circle */
      .sk-circle {
          width: 240px;
          height: 240px;
          position: relative;
      }
      
      .sk-circle .sk-child {
          width: 100%;
          height: 100%;
          position: absolute;
          left: 0;
          top: 0;
      }
      
      .sk-circle .sk-child:before {
          content: '';
          display: block;
          margin: 0 auto;
          width: 15%;
          height: 15%;
          background-color: #dc3545; /* ← VERMELHO */
          border-radius: 100%;
          animation: sk-circle-bounce-delay 1.2s infinite ease-in-out both;
      }
      
      .sk-circle .sk-child:nth-child(1) { transform: rotate(30deg); }
      .sk-circle .sk-child:nth-child(2) { transform: rotate(60deg); }
      .sk-circle .sk-child:nth-child(3) { transform: rotate(90deg); }
      .sk-circle .sk-child:nth-child(4) { transform: rotate(120deg); }
      .sk-circle .sk-child:nth-child(5) { transform: rotate(150deg); }
      .sk-circle .sk-child:nth-child(6) { transform: rotate(180deg); }
      .sk-circle .sk-child:nth-child(7) { transform: rotate(210deg); }
      .sk-circle .sk-child:nth-child(8) { transform: rotate(240deg); }
      .sk-circle .sk-child:nth-child(9) { transform: rotate(270deg); }
      .sk-circle .sk-child:nth-child(10) { transform: rotate(300deg); }
      .sk-circle .sk-child:nth-child(11) { transform: rotate(330deg); }
      .sk-circle .sk-child:nth-child(12) { transform: rotate(360deg); }
      
      .sk-circle .sk-child:nth-child(1):before { animation-delay: -1.1s; }
      .sk-circle .sk-child:nth-child(2):before { animation-delay: -1s; }
      .sk-circle .sk-child:nth-child(3):before { animation-delay: -0.9s; }
      .sk-circle .sk-child:nth-child(4):before { animation-delay: -0.8s; }
      .sk-circle .sk-child:nth-child(5):before { animation-delay: -0.7s; }
      .sk-circle .sk-child:nth-child(6):before { animation-delay: -0.6s; }
      .sk-circle .sk-child:nth-child(7):before { animation-delay: -0.5s; }
      .sk-circle .sk-child:nth-child(8):before { animation-delay: -0.4s; }
      .sk-circle .sk-child:nth-child(9):before { animation-delay: -0.3s; }
      .sk-circle .sk-child:nth-child(10):before { animation-delay: -0.2s; }
      .sk-circle .sk-child:nth-child(11):before { animation-delay: -0.1s; }
      .sk-circle .sk-child:nth-child(12):before { animation-delay: 0s; }
      
      @keyframes sk-circle-bounce-delay {
          0%, 80%, 100% {
              transform: scale(0);
          }
          40% {
              transform: scale(1);
          }
      }
      
      .spinner-center {
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          font-size: 48px; /* ✅ DOBROU: 24px → 48px */
          font-weight: bold;
          color: #dc3545; /* ← VERMELHO */
          z-index: 10;
      }
      
      .timer-message {
          text-align: center;
          padding: 12px 20px;
          background: #fff3cd;
          color: #856404;
          border: 1px solid #ffeaa7;
          border-radius: 8px;
          font-size: 0.9em;
          font-weight: 500;
          animation: slideIn 0.5s ease-out;
      }
      
      @keyframes slideIn {
          from {
              opacity: 0;
              transform: translateY(-10px);
          }
          to {
              opacity: 1;
              transform: translateY(0);
          }
      }
      
      .contact-message {
          background: linear-gradient(135deg, var(--imediato-light-blue), var(--imediato-dark-blue));
          color: var(--imediato-white);
          padding: 1rem 2rem;
          border-radius: 10px;
          margin: 1rem 0;
          text-align: center;
          font-weight: 500;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 0.5rem;
      }
      
      .contact-message i {
          font-size: 1.2rem;
      }
      
      /* Responsividade */
      @media (max-width: 768px) {
          #rpaModal {
              top: 70px !important;
              height: calc(100vh - 70px) !important;
          }
          
          #rpaModal .progress-header {
              padding: 1rem;
              flex-direction: column;
              gap: 0.75rem;
          }
          
          #rpaModal .progress-header h1 {
              font-size: 1.5rem;
              margin: 0 0 0.5rem 0;
          }
          
          #rpaModal .progress-header .company-logo {
              height: 50px;
              width: auto;
          }
          
          #rpaModal .progress-info {
              flex-direction: column;
              gap: 0.5rem;
          }
          
          .progress-text {
              font-size: 1.5rem;
          }
          
          .current-phase {
              font-size: 1rem;
          }
          
          .sub-phase {
              font-size: 0.85rem;
          }
          
          #rpaModal .results-container {
              grid-template-columns: 1fr;
              gap: 1.5rem;
          }
          
          #rpaModal .result-card {
              padding: 1.5rem;
          }
          
          .card-header {
              min-height: 60px;
              gap: 1rem;
          }
          
          .card-icon {
              width: 50px;
              height: 50px;
              font-size: 1.2rem;
          }
          
          .card-title h3 {
              font-size: 1.1rem;
          }
          
          .value {
              font-size: 2rem;
          }
          
          .action-buttons {
              flex-direction: column;
              padding: 0 1rem 1rem;
          }
          
          .btn {
              padding: 0.875rem 1.5rem;
              font-size: 0.95rem;
          }
      }
      
      @media (max-width: 480px) {
          #rpaModal .progress-header {
              padding: 0.75rem;
          }
          
          .current-phase {
              font-size: 0.9rem;
          }
          
          .sub-phase {
              font-size: 0.8rem;
          }
          
          .modal-content {
              padding: 0.75rem;
          }
          
          #rpaModal .result-card {
              padding: 1rem;
          }
          
          .card-icon {
              width: 45px;
              height: 45px;
              font-size: 1rem;
          }
          
          .value {
              font-size: 1.8rem;
          }
      }
  `;
  
  // ========================================
  // 2. CLASSE SPINNER TIMER
  // ========================================
  
  class SpinnerTimer {
      constructor() {
          this.initialDuration = 180; // 3 minutos em segundos
          this.extendedDuration = 120; // 2 minutos adicionais
          this.totalDuration = this.initialDuration;
          this.remainingSeconds = this.initialDuration;
          this.isRunning = false;
          this.isExtended = false;
          this.interval = null;
          
          this.elements = {
              spinnerCenter: null,
              timerMessage: null
          };
      }
      
      init() {
          this.elements.spinnerCenter = document.getElementById('spinnerCenter');
          this.elements.timerMessage = document.getElementById('timerMessage');
          
          console.log('🔄 Inicializando SpinnerTimer...');
          console.log('📍 spinnerCenter encontrado:', !!this.elements.spinnerCenter);
          console.log('📍 timerMessage encontrado:', !!this.elements.timerMessage);
          
          if (!this.elements.spinnerCenter) {
              console.warn('⚠️ Elementos do spinner timer não encontrados');
              return;
          }
          
          console.log('✅ Iniciando timer...');
          this.start();
      }
      
      start() {
          this.isRunning = true;
          this.isExtended = false;
          this.totalDuration = this.initialDuration;
          this.remainingSeconds = this.initialDuration;
          
          console.log('⏰ Timer iniciado:', this.remainingSeconds, 'segundos');
          
          this.interval = setInterval(() => {
              this.tick();
          }, 100);
      }
      
      tick() {
          this.remainingSeconds -= 0.1;
          
          if (this.remainingSeconds <= 0) {
              if (!this.isExtended) {
                  this.extendTimer();
                  return;
              } else {
                  this.finish();
                  return;
              }
          }
          
          this.updateDisplay();
      }
      
      extendTimer() {
          this.isExtended = true;
          this.totalDuration += this.extendedDuration;
          this.remainingSeconds = this.extendedDuration;
          
          if (this.elements.timerMessage) {
              this.elements.timerMessage.style.display = 'block';
          }
      }
      
      finish() {
          this.isRunning = false;
          this.remainingSeconds = 0;
          this.updateDisplay();
          
          clearInterval(this.interval);
      }
      
      updateDisplay() {
          const minutes = Math.floor(this.remainingSeconds / 60);
          const seconds = Math.floor(this.remainingSeconds % 60);
          const centiseconds = Math.floor((this.remainingSeconds % 1) * 10);
          
          const timerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}.${centiseconds}`;
          
          if (this.elements.spinnerCenter) {
              this.elements.spinnerCenter.textContent = timerText;
              console.log('🔄 Timer atualizado:', timerText);
          } else {
              console.warn('⚠️ spinnerCenter não encontrado para atualizar');
          }
      }
      
      stop() {
          this.isRunning = false;
          clearInterval(this.interval);
      }
      
      reset() {
          this.stop();
          this.isExtended = false;
          this.totalDuration = this.initialDuration;
          this.remainingSeconds = this.initialDuration;
          
          if (this.elements.timerMessage) {
              this.elements.timerMessage.style.display = 'none';
          }
          
          this.updateDisplay();
      }
  }

  // ========================================
  // 3. CLASSE PRINCIPAL DO MODAL
  // ========================================
  
  class ProgressModalRPA {
      constructor(sessionId) {
          this.apiBaseUrl = 'https://rpaimediatoseguros.com.br';
          this.sessionId = sessionId;
          this.progressInterval = null;
          this.isProcessing = true;
          
          // ✅ MUDANÇA 1: Spinner não é mais instanciado no construtor
          // Será inicializado apenas quando necessário (lazy loading)
          this.spinnerTimer = null;
          
          // ✅ CORREÇÃO: Inicializar o SpinnerTimer após receber sessionId
          this.setSessionId(sessionId); 
          
          // ✅ MUDANÇA 2: Nova propriedade para controlar inicialização única
          // Evita múltiplas instâncias do timer
          this.spinnerTimerInitialized = false;
          
          // Controle de atualizações
          this.initialEstimateUpdated = false;
          
          // Mensagens das 16 fases do RPA
          this.phaseMessages = {
              1: "🔄 Iniciando sistema...",
              2: "🔐 Fazendo login no sistema",
              3: "🌐 Acessando página de cotação",
              4: "📝 Validando dados pessoais",
              5: "🚗 Validando informações do veículo",
              6: "📍 Validando Endereço",
              7: "🅿️ Identificando perfil de endereço",
              8: "📊 Analisando perfil de risco",
              9: "🔍 Buscando melhores seguradoras",
              10: "📋 Coletando cotações disponíveis",
              11: "⚖️ Comparando propostas",
              12: "🎯 Selecionando melhor opção",
              13: "📄 Gerando proposta final",
              14: "✅ Validando dados finais",
              15: "⚙️ Finalizando processamento...",
              16: "🎉 Multi-cálculo completo"
          };
          
          // Submensagens das 16 fases
          this.phaseSubMessages = {
              1: "Preparando ambiente de cálculo",
              2: "Conectando com sistema seguro",
              3: "Carregando formulário de cotação",
              4: "Verificando informações pessoais",
              5: "Validando dados do veículo",
              6: "Confirmando endereço de risco",
              7: "Analisando localização",
              8: "Calculando perfil de risco",
              9: "Consultando seguradoras",
              10: "Coletando cotações",
              11: "Comparando propostas",
              12: "Selecionando melhor opção",
              13: "Gerando proposta",
              14: "Validando dados",
              15: "",
              16: "Seu seguro foi calculado com sucesso!"
          };
          
          // Percentuais fixos para cada fase
          // Mapeamento de fases para percentuais fixos (1-16, sendo 16 = 100%)
          this.phasePercentages = {
              0: 0,
              1: 6.25,   // 1/16 * 100
              2: 12.5,   // 2/16 * 100
              3: 18.75,  // 3/16 * 100
              4: 25,     // 4/16 * 100
              5: 31.25,  // 5/16 * 100
              6: 37.5,   // 6/16 * 100
              7: 43.75,  // 7/16 * 100
              8: 50,     // 8/16 * 100
              9: 56.25,  // 9/16 * 100
              10: 62.5,  // 10/16 * 100
              11: 68.75, // 11/16 * 100
              12: 75,    // 12/16 * 100
              13: 81.25, // 13/16 * 100
              14: 87.5,  // 14/16 * 100
              15: 93.75, // 15/16 * 100
              16: 100    // 16/16 * 100 (finalização completa)
          };
          
          console.log('🚀 ProgressModalRPA inicializado com sessionId:', this.sessionId);
      }
      
      setSessionId(sessionId) {
          this.sessionId = sessionId;
          console.log('🔄 SessionId atualizado:', this.sessionId);
          
          // ✅ MELHORIA CONSENSADA: Debounce para prevenir chamadas múltiplas
          
          // Limpar timeout anterior se existir
          if (this.setSessionIdTimeout) {
              clearTimeout(this.setSessionIdTimeout);
          }
          
          // ✅ MUDANÇA 3: Verificação antes de inicializar
          // Só inicializa se ainda não foi inicializado
          if (!this.spinnerTimerInitialized) {
              // ✅ CORREÇÃO: Inicializar IMEDIATAMENTE quando o modal abre
              this.initSpinnerTimer();
              // Marca como inicializado
              this.spinnerTimerInitialized = true;
          }
      }
      
      initSpinnerTimer() {
          // ✅ MUDANÇA 4: Método dedicado para inicializar o spinner
          
          // Verifica se já existe uma instância
          if (!this.spinnerTimer) {
              // Cria nova instância do SpinnerTimer
              this.spinnerTimer = new SpinnerTimer();
              
              // Inicializa o timer (busca elementos DOM)
              this.spinnerTimer.init();
              
              // Inicia a contagem do timer
              this.spinnerTimer.start();
              
              // Log para debug
              console.log('✅ SpinnerTimer inicializado e iniciado');
          }
      }
      
      stopSpinnerTimer() {
          // ✅ MUDANÇA 5: Método para parar e esconder o spinner
          
          try {
              // PARTE 1: Parar o timer se ele existir
              if (this.spinnerTimer) {
                  // Chama finish() que:
                  // - Para a contagem (clearInterval)
                  // - Zera o tempo restante
                  // - Atualiza display para 00:00.0
                  this.spinnerTimer.finish();
                  
                  // Remove a referência do timer
                  this.spinnerTimer = null;
                  
                  // Log para debug
                  console.log('⏹️ SpinnerTimer parado');
              }
              
              // PARTE 2: Esconder o container do spinner
              const spinnerContainer = document.getElementById('spinnerTimerContainer');
              if (spinnerContainer) {
                  // Esconde completamente o spinner
                  // Usa display: none para remover do fluxo visual
                  spinnerContainer.style.display = 'none';
                  
                  // Log para debug
                  console.log('✅ Spinner timer escondido');
              }
          } catch (error) {
              console.error('Erro ao parar spinner timer:', error);
              
              // Fallback: pelo menos esconder o container
              const container = document.getElementById('spinnerTimerContainer');
              if (container) {
                  container.style.display = 'none';
                  console.log('✅ Spinner escondido via fallback');
              }
          }
      }
      
      startProgressPolling() {
          if (!this.sessionId) {
              console.error('❌ Session ID não encontrado');
              return;
          }
          
          console.log('🔄 Iniciando polling do progresso...');
          this.pollCount = 0;
          this.maxPolls = 300; // 300 tentativas = 600 segundos (10 minutos, 2s cada)
          
          this.progressInterval = setInterval(() => {
              this.pollCount++;
              console.log(`🔄 Polling ${this.pollCount}/${this.maxPolls}`);
              
              if (this.pollCount > this.maxPolls) {
                  console.error('❌ Timeout: Processamento demorou mais de 10 minutos');
                  this.stopProgressPolling();
                  
                  // ✅ MUDANÇA 6: Para o spinner em caso de timeout
                  this.stopSpinnerTimer();
                  
                  this.showErrorAlert('O processamento está demorando mais que o esperado (10 minutos). Tente novamente ou entre em contato conosco.');
                  return;
              }
              
              this.updateProgress();
          }, 2000);
      }
      
      stopProgressPolling() {
          if (this.progressInterval) {
              clearInterval(this.progressInterval);
              this.progressInterval = null;
              console.log('⏹️ Polling interrompido');
          }
      }
      
      async updateProgress() {
          if (!this.sessionId) return;
          
          try {
              const response = await fetch(`${this.apiBaseUrl}/api/rpa/progress/${this.sessionId}`);
              const data = await response.json();
              
              console.log('📊 Dados do progresso:', data);
              console.log('📊 Objeto progress:', data.progress);
              console.log('📊 Etapa atual:', data.progress?.etapa_atual);
              console.log('📊 Fase atual:', data.progress?.fase_atual);
              console.log('📊 Status:', data.progress?.status);
              console.log('📊 Mensagem:', data.progress?.mensagem);
              console.log('📊 Código de erro:', data.progress?.error_code);
              console.log('📊 Código de status:', data.progress?.status_code);
              
              if (data.success) {
                  const progressData = data.progress;
                  const currentStatus = progressData.status || 'processing';
                  const mensagem = progressData.mensagem || '';
                  
                  // Salvar dados de progresso para uso em handleRPAError
                  this.lastProgressData = progressData;
                  
                  console.log('🔍 DEBUG - Dados completos do progresso:', {
                      fase_atual: progressData.fase_atual,
                      etapa_atual: progressData.etapa_atual,
                      total_etapas: progressData.total_etapas,
                      status: progressData.status,
                      mensagem: progressData.mensagem,
                      percentual: progressData.percentual,
                      estimativas: progressData.estimativas,
                      dados_extra: progressData.dados_extra,
                      plano_recomendado: progressData.dados_extra?.plano_recomendado,
                      plano_alternativo: progressData.dados_extra?.plano_alternativo
                  });
                  
                  // Verificar se há erro na mensagem, status ou códigos
                  const errorCode = progressData.error_code || progressData.status_code || progressData.dados_extra?.error_code || progressData.dados_extra?.status_code;
                  if (this.isErrorStatus(currentStatus, mensagem, errorCode)) {
                      console.error('❌ Erro detectado no RPA:', { 
                          status: currentStatus, 
                          mensagem, 
                          errorCode 
                      });
                      this.handleRPAError(mensagem || `Status: ${currentStatus}`, errorCode);
                      return;
                  }
                  
                  // Lógica corrigida: usar fase 16 quando status for 'success'
                  let currentPhase = progressData.fase_atual || progressData.etapa_atual || 1;
                  
                  // Se status é 'success', forçar fase 16 (finalização completa)
                  if (currentStatus === 'success') {
                      currentPhase = 16;
                      console.log('🎉 Status success detectado → forçando fase 16 (finalização completa)');
                  }
                  
                  // Usar percentual baseado na fase (1-16)
                  let percentual = this.phasePercentages[currentPhase] || 0;
                  
                  console.log(`📈 Fase ${currentPhase}: ${percentual}% (Status: ${currentStatus})`);
                  console.log(`📊 Percentual calculado pela fase: ${percentual}`);
                  
                  // Atualizar elementos do modal diretamente
                  const totalEtapas = 16; // Sempre 16 fases (1-15 processamento + 16 finalização)
                  this.updateProgressElements(percentual, currentPhase, currentStatus, progressData, totalEtapas);
                  
                  // Verificar se há estimativas disponíveis
                  if (progressData.estimativas?.dados || progressData.dados_extra?.estimativas_tela_5) {
                      this.updateInitialEstimate(progressData);
                  }
                  
                  // Verificar se há resultados finais
                  if (progressData.dados_extra || currentStatus === 'success') {
                      this.updateResults(progressData);
                      this.updateSuccessHeader();
                      
                      if (currentStatus === 'success') {
                          console.log('🎉 RPA concluído com sucesso!');
                          this.stopProgressPolling();
                          this.isProcessing = false;
                          
                          // ✅ MUDANÇA 7: Para o spinner em caso de sucesso
                          this.stopSpinnerTimer();
                      }
                  }
              }
          } catch (error) {
              console.error('❌ Erro ao atualizar progresso:', error);
          }
      }
      
      /**
       * TABELA DE CÓDIGOS DE ERRO DO RPA
       * Baseada na tabela oficial do executar_rpa_error_handler.py
       */
      getErrorTable() {
          return {
              // ERROS DE VALIDAÇÃO E CONFIGURAÇÃO (1000-1999)
              1000: {
                  "category": "VALIDATION_ERROR",
                  "description": "Parâmetros obrigatórios ausentes ou inválidos",
                  "message": "Um ou mais parâmetros obrigatórios não foram fornecidos ou são inválidos",
                  "action": "Verificar se todos os parâmetros obrigatórios estão presentes e com formato correto"
              },
              1001: {
                  "category": "VALIDATION_ERROR", 
                  "description": "Formato de CPF inválido",
                  "message": "O CPF fornecido não possui formato válido (deve ter 11 dígitos numéricos)",
                  "action": "Verificar se o CPF possui exatamente 11 dígitos numéricos"
              },
              1002: {
                  "category": "VALIDATION_ERROR",
                  "description": "Formato de email inválido", 
                  "message": "O email fornecido não possui formato válido",
                  "action": "Verificar se o email possui formato válido (ex: usuario@dominio.com)"
              },
              1003: {
                  "category": "VALIDATION_ERROR",
                  "description": "Formato de CEP inválido",
                  "message": "O CEP fornecido não possui formato válido (deve ter 8 dígitos numéricos)",
                  "action": "Verificar se o CEP possui exatamente 8 dígitos numéricos"
              },
              1004: {
                  "category": "VALIDATION_ERROR",
                  "description": "JSON malformado ou inválido",
                  "message": "O JSON fornecido não pode ser interpretado corretamente",
                  "action": "Verificar se o JSON está formatado corretamente e é válido"
              },
              
              // ERROS DE CHROME E WEBDRIVER (2000-2999)
              2000: {
                  "category": "CHROME_ERROR",
                  "description": "ChromeDriver não encontrado ou inacessível",
                  "message": "Não foi possível encontrar ou acessar o ChromeDriver necessário para execução",
                  "action": "Verificar se o ChromeDriver está presente em ./chromedriver/chromedriver-win64/chromedriver.exe"
              },
              2001: {
                  "category": "CHROME_ERROR",
                  "description": "Falha ao criar instância do Chrome",
                  "message": "O Chrome não pôde ser iniciado ou configurado corretamente",
                  "action": "Verificar se o Chrome está instalado e se há memória disponível suficiente"
              },
              2002: {
                  "category": "CHROME_ERROR",
                  "description": "Sessão do Chrome não pôde ser criada",
                  "message": "Falha ao estabelecer sessão com o navegador Chrome",
                  "action": "Fechar outras instâncias do Chrome e verificar configurações de firewall/antivírus"
              },
              2003: {
                  "category": "CHROME_ERROR",
                  "description": "Chrome fechou inesperadamente",
                  "message": "O navegador Chrome foi fechado durante a execução",
                  "action": "Verificar logs do Chrome e disponibilidade de memória do sistema"
              },
              
              // ERROS DE NAVEGAÇÃO E ELEMENTOS (3000-3999)
              3000: {
                  "category": "NAVIGATION_ERROR",
                  "description": "Falha ao navegar para URL",
                  "message": "Não foi possível acessar a URL especificada",
                  "action": "Verificar se a URL está correta e se há conectividade com a internet"
              },
              3001: {
                  "category": "ELEMENT_ERROR",
                  "description": "Elemento não encontrado na página",
                  "message": "O elemento especificado não foi encontrado na página atual",
                  "action": "Verificar se o seletor está correto e se a página carregou completamente"
              },
              3002: {
                  "category": "ELEMENT_ERROR",
                  "description": "Elemento não está clicável",
                  "message": "O elemento foi encontrado mas não pode ser clicado",
                  "action": "Aguardar carregamento completo e verificar se o elemento está visível e habilitado"
              },
              3003: {
                  "category": "ELEMENT_ERROR",
                  "description": "Elemento obsoleto (Stale Element Reference)",
                  "message": "O elemento foi encontrado mas tornou-se obsoleto durante a operação",
                  "action": "Recarregar a referência do elemento e tentar novamente"
              },
              3004: {
                  "category": "ELEMENT_ERROR",
                  "description": "Elemento interceptado por outro",
                  "message": "O elemento não pode ser clicado pois está sendo interceptado por outro elemento",
                  "action": "Fechar modais/overlays ou rolar para o elemento antes de clicar"
              },
              3005: {
                  "category": "ELEMENT_ERROR",
                  "description": "Elemento não interativo",
                  "message": "O elemento encontrado não é interativo (não pode ser clicado ou preenchido)",
                  "action": "Verificar se o elemento correto foi selecionado e se está habilitado"
              },
              
              // ERROS DE TIMEOUT E CARREGAMENTO (4000-4999)
              4000: {
                  "category": "TIMEOUT_ERROR",
                  "description": "Timeout ao aguardar carregamento da página",
                  "message": "A página não carregou completamente dentro do tempo limite especificado",
                  "action": "Aumentar timeout ou verificar conectividade com a internet"
              },
              4001: {
                  "category": "TIMEOUT_ERROR",
                  "description": "Timeout ao aguardar elemento aparecer",
                  "message": "O elemento esperado não apareceu na página dentro do tempo limite",
                  "action": "Verificar se o seletor está correto e se a página é a esperada"
              },
              4002: {
                  "category": "TIMEOUT_ERROR",
                  "description": "Timeout ao aguardar estabilização do DOM",
                  "message": "O DOM da página não estabilizou dentro do tempo limite especificado",
                  "action": "Aumentar timeout de estabilização ou usar fallback tradicional"
              },
              4003: {
                  "category": "TIMEOUT_ERROR",
                  "description": "Timeout ao aguardar elemento ficar clicável",
                  "message": "O elemento não ficou clicável dentro do tempo limite",
                  "action": "Verificar se o elemento está realmente habilitado e se as condições foram satisfeitas"
              },
              
              // ERROS DE TELA ESPECÍFICA (6000-6999)
              6000: {
                  "category": "SCREEN_ERROR",
                  "description": "Falha na Tela 1 - Seleção do tipo de seguro",
                  "message": "Não foi possível selecionar o tipo de seguro 'Carro' na primeira tela",
                  "action": "Verificar se o site ainda possui a mesma estrutura e se o botão Carro está presente"
              },
              6001: {
                  "category": "SCREEN_ERROR",
                  "description": "Falha na Tela 2 - Inserção da placa",
                  "message": "Não foi possível inserir a placa do veículo na segunda tela",
                  "action": "Verificar se o campo de placa está presente e editável"
              },
              6002: {
                  "category": "SCREEN_ERROR",
                  "description": "Falha na Tela 3 - Confirmação do veículo",
                  "message": "Não foi possível confirmar o veículo na terceira tela",
                  "action": "Verificar se a confirmação do veículo está aparecendo e se os elementos estão presentes"
              },
              6003: {
                  "category": "SCREEN_ERROR",
                  "description": "Falha na Tela 4 - Veículo segurado",
                  "message": "Não foi possível responder sobre veículo já segurado na quarta tela",
                  "action": "Verificar se a pergunta sobre veículo segurado está aparecendo"
              },
              6004: {
                  "category": "SCREEN_ERROR",
                  "description": "Falha na Tela 5 - Estimativa inicial",
                  "message": "Não foi possível navegar pela tela de estimativa inicial",
                  "action": "Verificar se a tela de estimativa está carregando corretamente"
              },
              6005: {
                  "category": "SCREEN_ERROR",
                  "description": "Falha na Tela 6 - Tipo de combustível",
                  "message": "Não foi possível selecionar o tipo de combustível na sexta tela",
                  "action": "Verificar se a tela de combustível está carregando e se os elementos estão presentes"
              },
              6006: {
                  "category": "SCREEN_ERROR",
                  "description": "Falha na Tela 7 - Endereço de pernoite",
                  "message": "Não foi possível inserir o endereço de pernoite na sétima tela",
                  "action": "Verificar se o campo CEP está presente e se o CEP é válido"
              },
              6007: {
                  "category": "SCREEN_ERROR",
                  "description": "Falha na Tela 8 - Finalidade do veículo",
                  "message": "Não foi possível selecionar a finalidade do veículo na oitava tela",
                  "action": "Verificar se a tela de finalidade está carregando e se o botão com ID específico está presente"
              },
              6008: {
                  "category": "SCREEN_ERROR",
                  "description": "Falha na Tela 9 - Dados pessoais",
                  "message": "Não foi possível preencher os dados pessoais na nona tela",
                  "action": "Verificar se todos os campos estão presentes e editáveis"
              },
              
              // ERROS DE SISTEMA E RECURSOS (7000-7999)
              7000: {
                  "category": "SYSTEM_ERROR",
                  "description": "Memória insuficiente",
                  "message": "O sistema não possui memória suficiente para executar o RPA",
                  "action": "Fechar outros programas, reiniciar o sistema ou aumentar memória disponível"
              },
              7001: {
                  "category": "SYSTEM_ERROR",
                  "description": "Disco cheio",
                  "message": "Não há espaço suficiente em disco para salvar arquivos temporários",
                  "action": "Liberar espaço em disco e verificar se há espaço suficiente"
              },
              7002: {
                  "category": "SYSTEM_ERROR",
                  "description": "Permissões insuficientes",
                  "message": "O usuário não possui permissões suficientes para executar operações necessárias",
                  "action": "Executar como administrador ou verificar permissões da pasta de trabalho"
              },
              7003: {
                  "category": "SYSTEM_ERROR",
                  "description": "Arquivo não encontrado",
                  "message": "Um arquivo necessário para execução não foi encontrado",
                  "action": "Verificar se o arquivo está no local correto e se há permissões de acesso"
              },
              
              // ERROS DE TELA FINAL E RESULTADOS (9000-9999)
              9003: {
                  "category": "MANUAL_QUOTATION_ERROR",
                  "description": "Cotação manual necessária",
                  "message": "Não foi possível efetuar o cálculo nesse momento. O corretor de seguros já foi notificado e logo entrará em contato para te auxiliar a encontrar as melhores opções.",
                  "action": "O corretor de seguros entrará em contato em breve para auxiliar com a cotação manual."
              },
              9004: {
                  "category": "FINAL_SCREEN_ERROR",
                  "description": "Tela final não detectada",
                  "message": "Infelizmente não foi possível, devido a problemas técnicos, efetuar o cálculo agora. Mas a Imediato Seguros fará o cálculo manualmente em instantes e entrará em contato",
                  "action": "A Imediato Seguros fará o cálculo manualmente e entrará em contato em breve"
              }
          };
      }
      
      /**
       * Verificar se o status indica erro
       */
      isErrorStatus(status, mensagem, errorCode = null) {
          const statusLower = status.toLowerCase();
          const mensagemLower = mensagem.toLowerCase();
          
          // Status de erro conhecidos
          const errorStatuses = [
              'error', 'failed', 'failure', 'exception', 'timeout', 
              'denied', 'invalid', 'blocked', 'cancelled', 'aborted'
          ];
          
          // Palavras-chave de erro na mensagem
          const errorKeywords = [
              'falhou', 'erro', 'failed', 'error', 'exception', 
              'timeout', 'denied', 'invalid', 'blocked', 'cancelled'
          ];
          
          // Códigos de erro HTTP comuns + RPA específicos
          const errorCodes = [
              '400', '401', '403', '404', '405', '408', '409', '410', 
              '422', '429', '500', '501', '502', '503', '504',
              '9003',  // Cotação Manual Necessária
              '9004'   // Tela Final Não Detectada
          ];
          
          // Verificar código de erro específico da tabela RPA
          if (errorCode) {
              const errorTable = this.getErrorTable();
              if (errorTable[errorCode]) {
                  return true;
              }
          }
          
          // Verificar código de erro específico HTTP
          if (errorCode) {
              const codeStr = String(errorCode);
              for (const code of errorCodes) {
                  if (codeStr.includes(code)) {
                      return true;
                  }
              }
          }
          
          // Verificar status
          for (const errorStatus of errorStatuses) {
              if (statusLower.includes(errorStatus)) {
                  return true;
              }
          }
          
          // Verificar mensagem
          for (const keyword of errorKeywords) {
              if (mensagemLower.includes(keyword)) {
                  return true;
              }
          }
          
          // Verificar códigos de erro na mensagem
          for (const code of errorCodes) {
              if (mensagemLower.includes(`erro ${code}`) || 
                  mensagemLower.includes(`error ${code}`) ||
                  mensagemLower.includes(`code ${code}`) ||
                  mensagemLower.includes(`${code} error`)) {
                  return true;
              }
          }
          
          return false;
      }
      
      /**
       * Tratar erro do RPA
       */
      handleRPAError(mensagem, errorCode = null) {
          console.error('🚨 Tratando erro do RPA:', { mensagem, errorCode });
          
          // Parar o polling
          this.stopProgressPolling();
          this.isProcessing = false;
          
          // ✅ MUDANÇA 8: Para o spinner em caso de erro
          this.stopSpinnerTimer();
          
          // Remover modal de progresso
          const modal = document.getElementById('rpaModal');
          if (modal) {
              modal.remove();
          }
          
          // ✅ TODOS os erros são tratados como cotação manual
          if (typeof Swal !== 'undefined') {
              Swal.fire({
                  title: '📞 Cotação Manual Necessária',
                  text: 'Não foi possível efetuar o cálculo nesse momento. Um especialista da Imediato Seguros fará o cálculo manualmente e entrará em contato para envia-lo à você em seguida.',
                  icon: 'info',
                  confirmButtonText: 'Entendi',
                  confirmButtonColor: '#3085d6'
              });
          }
      }
      
      /**
       * Mostrar SweetAlert de erro
       */
      showErrorAlert(mensagem, acao = null, errorCode = null) {
          // ✅ SEMPRE mostrar cotação manual
          if (typeof Swal !== 'undefined') {
              Swal.fire({
                  title: '📞 Cotação Manual Necessária',
                  text: 'Não foi possível efetuar o cálculo nesse momento. Um especialista da Imediato Seguros fará o cálculo manualmente e entrará em contato para envia-lo à você em seguida.',
                  icon: 'info',
                  confirmButtonText: 'Entendi',
                  confirmButtonColor: '#3085d6'
              });
          } else {
              // Fallback para alert nativo se SweetAlert não estiver disponível
              alert('📞 Cotação Manual Necessária\n\nNão foi possível efetuar o cálculo nesse momento. Um especialista da Imediato Seguros fará o cálculo manualmente e entrará em contato para envia-lo à você em seguida.');
          }
      }
      
      updateProgressElements(percentual, currentPhase, currentStatus, progressData, totalEtapas = 16) {
          console.log(`🔄 Atualizando elementos: ${percentual}%, Fase ${currentPhase}, Status: ${currentStatus}`);
          
          // Seletores corretos baseados no HTML injetado
          const progressText = document.querySelector('#rpaModal .progress-text');
          const currentPhaseElement = document.querySelector('#rpaModal .current-phase');
          const subPhaseElement = document.querySelector('#rpaModal .sub-phase');
          const stageInfo = document.querySelector('#rpaModal .stage-info');
          const progressFill = document.querySelector('#rpaModal .progress-bar-fill');
          
          if (progressText) {
              progressText.textContent = `${Math.round(percentual)}%`;
              console.log('✅ Progress text atualizado:', progressText.textContent);
          }
          
          if (currentPhaseElement) {
              const message = this.getPhaseMessage(currentPhase);
              currentPhaseElement.textContent = message;
              console.log('✅ Current phase atualizado:', message);
          }
          
          if (subPhaseElement) {
              const subMessage = this.getPhaseSubMessage(currentPhase);
              subPhaseElement.textContent = subMessage;
              console.log('✅ Sub phase atualizado:', subMessage);
          }
          
          if (stageInfo) {
              stageInfo.textContent = `Fase ${currentPhase} de ${totalEtapas}`;
              console.log('✅ Stage info atualizado:', stageInfo.textContent);
          }
          
          if (progressFill) {
              progressFill.style.width = `${percentual}%`;
              console.log('✅ Progress fill atualizado:', progressFill.style.width);
          }
      }
      
      getPhaseMessage(phaseNumber) {
          return this.phaseMessages[phaseNumber] || `Fase ${phaseNumber}`;
      }
      
      getPhaseSubMessage(phaseNumber) {
          return this.phaseSubMessages[phaseNumber] || '';
      }
      
      updateInitialEstimate(data) {
          if (this.initialEstimateUpdated) return;
          
          console.log('💰 Atualizando estimativa inicial:', data);
          
          // Buscar estimativas em diferentes locais possíveis
          const estimativas = data.estimativas?.dados || 
                             data.dados_extra?.estimativas_tela_5 || 
                             data.historico?.estimativas ||
                             data.progress?.estimativas;
          
          console.log('💰 Estimativas encontradas:', estimativas);
          
          if (estimativas) {
              let valorInicial = null;
              
              // Tentar diferentes estruturas de dados
              if (estimativas.coberturas_detalhadas?.valor_total) {
                  valorInicial = estimativas.coberturas_detalhadas.valor_total;
              } else if (estimativas.valor_total) {
                  valorInicial = estimativas.valor_total;
              } else if (estimativas.valor) {
                  valorInicial = estimativas.valor;
              } else if (typeof estimativas === 'number') {
                  valorInicial = estimativas;
              }
              
              if (valorInicial) {
                  const valorFormatado = this.formatCurrency(valorInicial);
                  console.log('💰 Valor inicial formatado:', valorFormatado);
                  
                  const initialEstimateElement = document.getElementById('initialEstimate');
                  if (initialEstimateElement) {
                      initialEstimateElement.textContent = valorFormatado;
                      this.highlightInitialEstimate();
                  }
                  
                  this.initialEstimateUpdated = true;
              } else {
                  console.log('⚠️ Valor inicial não encontrado nas estimativas');
              }
          } else {
              console.log('⚠️ Nenhuma estimativa encontrada nos dados');
          }
      }
      
      highlightInitialEstimate() {
          const estimateCard = document.querySelector('#rpaModal .result-card.recommended');
          if (estimateCard) {
              estimateCard.style.animation = 'pulse 2s infinite';
          }
      }
      
      updateResults(data) {
          console.log('📊 Atualizando resultados finais:', data);
          console.log('📊 Estrutura completa dos dados:', JSON.stringify(data, null, 2));
          
          // Buscar resultados em múltiplas estruturas possíveis
          let resultados = null;
          let planoRecomendado = null;
          let planoAlternativo = null;
          
          // Tentar estrutura 1: resultados_finais.dados.dados_finais
          if (data.resultados_finais?.dados?.dados_finais) {
              resultados = data.resultados_finais.dados.dados_finais;
              planoRecomendado = resultados.plano_recomendado;
              planoAlternativo = resultados.plano_alternativo;
              console.log('✅ Dados encontrados em resultados_finais.dados.dados_finais');
          }
          
          // Tentar estrutura 2: timeline[final].dados_extra
          if (!planoRecomendado && data.timeline) {
              const finalEntry = data.timeline.find(entry => entry.etapa === 'final');
              if (finalEntry?.dados_extra) {
                  planoRecomendado = finalEntry.dados_extra.plano_recomendado;
                  planoAlternativo = finalEntry.dados_extra.plano_alternativo;
                  console.log('✅ Dados encontrados em timeline[final].dados_extra');
              }
          }
          
          // Tentar estrutura 3: dados_extra direto (estrutura antiga)
          if (!planoRecomendado && data.dados_extra) {
              planoRecomendado = data.dados_extra.plano_recomendado;
              planoAlternativo = data.dados_extra.plano_alternativo;
              console.log('✅ Dados encontrados em dados_extra direto');
          }
          
          console.log('🔍 DEBUG - Estrutura completa:', {
              resultados_finais: data.resultados_finais,
              timeline_final: data.timeline?.find(entry => entry.etapa === 'final'),
              dados_extra_direto: data.dados_extra,
              plano_recomendado_encontrado: planoRecomendado,
              plano_alternativo_encontrado: planoAlternativo
          });
          
          if (planoRecomendado && planoAlternativo) {
              console.log('📊 Resultados encontrados:', { planoRecomendado, planoAlternativo });
              
              // Atualizar valores principais
              this.updateCardValue('recommendedValue', planoRecomendado.valor);
              this.updateCardValue('alternativeValue', planoAlternativo.valor);
              
              // Atualizar detalhes do plano recomendado
              this.updateCardDetails('recommended', planoRecomendado);
              
              // Atualizar detalhes do plano alternativo
              this.updateCardDetails('alternative', planoAlternativo);
          } else {
              console.log('⚠️ Nenhum resultado final encontrado em nenhuma estrutura');
          }
      }
      
      updateCardValue(elementId, valor) {
          console.log(`🔍 DEBUG - updateCardValue chamado:`, { elementId, valor, tipo: typeof valor });
          
          if (valor) {
              const element = document.querySelector(`#rpaModal #${elementId}`);
              console.log(`🔍 DEBUG - Elemento encontrado:`, element);
              
              if (element) {
                  const valorFormatado = this.formatCurrency(valor);
                  element.textContent = valorFormatado;
                  console.log(`✅ Valor ${elementId} atualizado:`, valorFormatado);
              } else {
                  console.error(`❌ Elemento #${elementId} não encontrado no DOM`);
              }
          } else {
              console.warn(`⚠️ Valor vazio para ${elementId}:`, valor);
          }
      }
      
      updateCardDetails(prefix, plano) {
          if (!plano) return;
          
          console.log(`🔍 DEBUG - Atualizando detalhes do plano ${prefix}:`, plano);
          
          // Função para formatar check positivo/negativo
          const formatCheck = (value) => {
              return value ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>';
          };
          
          // Função para formatar valores monetários
          const formatMoney = (value) => {
              if (!value || value === '-') return '-';
              if (typeof value === 'string' && value.includes('R$')) {
                  return value;
              }
              // Se for número, formatar como moeda
              if (typeof value === 'number') {
                  return this.formatCurrency(value);
              }
              // Se for string com números, tentar converter
              const numValue = parseFloat(value.toString().replace(/[^\d,.-]/g, '').replace(',', '.'));
              if (!isNaN(numValue)) {
                  return this.formatCurrency(numValue);
              }
              return `R$ ${value}`;
          };
          
          // Atualizar todos os campos
          this.updateField(`${prefix}FormaPagamento`, plano.forma_pagamento || '-');
          this.updateField(`${prefix}Parcelamento`, plano.parcelamento || '-');
          this.updateField(`${prefix}ValorMercado`, plano.valor_mercado || '-');
          this.updateField(`${prefix}ValorFranquia`, formatMoney(plano.valor_franquia || '-'));
          this.updateField(`${prefix}TipoFranquia`, plano.tipo_franquia || '-');
          this.updateField(`${prefix}Assistencia`, formatCheck(plano.assistencia));
          this.updateField(`${prefix}Vidros`, formatCheck(plano.vidros));
          this.updateField(`${prefix}CarroReserva`, formatCheck(plano.carro_reserva));
          this.updateField(`${prefix}DanosMateriais`, formatMoney(plano.danos_materiais || '-'));
          this.updateField(`${prefix}DanosCorporais`, formatMoney(plano.danos_corporais || '-'));
          this.updateField(`${prefix}DanosMorais`, formatMoney(plano.danos_morais || '-'));
          this.updateField(`${prefix}MorteInvalidez`, formatMoney(plano.morte_invalidez || '-'));
      }
      
      updateField(elementId, value) {
          const element = document.querySelector(`#rpaModal #${elementId}`);
          if (element) {
              element.innerHTML = value;
              console.log(`✅ Campo ${elementId} atualizado:`, value);
          }
      }
      
      updateSuccessHeader() {
          const progressHeader = document.querySelector('#rpaModal .progress-header');
          if (progressHeader) {
              const contactMessage = document.createElement('p');
              contactMessage.className = 'contact-message';
              contactMessage.innerHTML = '<i class="fas fa-phone"></i> Um especialista da Imediato Seguros entrará em contato em instantes para passar os detalhes!';
              
              progressHeader.appendChild(contactMessage);
          }
      }
      
      formatCurrency(value) {
          if (!value) return 'R$ 0,00';
          
          // Se já está formatado como moeda brasileira, retornar como está
          if (typeof value === 'string' && value.includes('R$')) {
              console.log('💰 Valor já formatado:', value);
              return value;
          }
          
          // Converter para número - tratar valores já formatados
          let numericValue;
          if (typeof value === 'string') {
              // Remover "R$", espaços e converter vírgula para ponto
              const cleanValue = value.replace(/[R$\s]/g, '').replace(',', '.');
              numericValue = parseFloat(cleanValue);
          } else {
              numericValue = parseFloat(value);
          }
          
          // Verificar se é um número válido
          if (isNaN(numericValue)) {
              console.warn('⚠️ Valor inválido para formatação:', value);
              return 'R$ 0,00';
          }
          
          // Formatar como moeda brasileira
          const formatted = numericValue.toLocaleString('pt-BR', {
              style: 'currency',
              currency: 'BRL'
          });
          
          console.log('💰 Valor formatado:', value, '→', formatted);
          return formatted;
      }
  }
  
  // ========================================
  // 3. CLASSE DE VALIDAÇÃO DE FORMULÁRIO
  // ========================================
  
  class FormValidator {
      constructor() {
          this.config = {
              USE_PHONE_API: true,
              APILAYER_KEY: 'dce92fa84152098a3b5b7b8db24debbc',
              SAFETY_BASE: 'https://optin.safetymails.com/main/safetyoptin/20a7a1c297e39180bd80428ac13c363e882a531f/9bab7f0c2711c5accfb83588c859dc1103844a94/',
              VALIDAR_PH3A: false
          };
      }
      
      // Helper functions
      onlyDigits(s) {
          return (s || '').replace(/\D+/g, '');
      }
      
      toUpperNospace(s) {
          return (s || '').toUpperCase().trim();
      }
      
      // CPF Validation
      validarCPFFormato(cpf) {
          const cpfLimpo = this.onlyDigits(cpf);
          return cpfLimpo.length === 11 && !/^(\d)\1{10}$/.test(cpfLimpo);
      }
      
      validarCPFAlgoritmo(cpf) {
          cpf = this.onlyDigits(cpf);
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
      
      async validateCPF(cpf) {
          if (!cpf) return { ok: false, reason: 'vazio' };
          
          const formatoOk = this.validarCPFFormato(cpf);
          const algoritmoOk = this.validarCPFAlgoritmo(cpf);
          
          return {
              ok: formatoOk && algoritmoOk,
              reason: !formatoOk ? 'formato' : (!algoritmoOk ? 'algoritmo' : 'ok')
          };
      }
      
      // CEP Validation
      async validateCEP(cep) {
          const cepLimpo = this.onlyDigits(cep);
          if (cepLimpo.length !== 8) {
              return { ok: false, reason: 'formato' };
          }
          
          try {
              const response = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`);
              const data = await response.json();
              
              return {
                  ok: !data.erro,
                  reason: data.erro ? 'nao_encontrado' : 'ok',
                  viacep: data
              };
          } catch (error) {
              return { ok: false, reason: 'erro_api' };
          }
      }
      
      // Placa Validation
      validarPlacaFormato(p) {
          const placaLimpa = p.toUpperCase().replace(/[^A-Z0-9]/g, '');
          const antigo = /^[A-Z]{3}[0-9]{4}$/;
          const mercosul = /^[A-Z]{3}[0-9][A-Z][0-9]{2}$/;
          return antigo.test(placaLimpa) || mercosul.test(placaLimpa);
      }
      
      extractVehicleFromPlacaFipe(apiJson) {
          const r = apiJson && (apiJson.informacoes_veiculo || apiJson);
          if (!r || typeof r !== 'object') return { marcaTxt: '', anoModelo: '', tipoVeiculo: '' };
          
          const fabricante = r.marca || '';
          const modelo = r.modelo || '';
          const anoMod = r.ano || r.ano_modelo || '';
          
          let tipoVeiculo = '';
          if (r.segmento) {
              const segmento = r.segmento.toLowerCase();
              if (segmento.includes('moto')) {
                  tipoVeiculo = 'moto';
              } else if (segmento.includes('auto')) {
                  tipoVeiculo = 'carro';
              } else {
                  const modeloLower = modelo.toLowerCase();
                  const marcaLower = fabricante.toLowerCase();
                  
                  if (marcaLower.includes('honda') || marcaLower.includes('yamaha') || 
                     marcaLower.includes('suzuki') || marcaLower.includes('kawasaki') ||
                     modeloLower.includes('cg') || modeloLower.includes('cb') || 
                     modeloLower.includes('fazer') || modeloLower.includes('ninja')) {
                      tipoVeiculo = 'moto';
                  } else {
                      tipoVeiculo = 'carro';
                  }
              }
          } else {
              const modeloLower = modelo.toLowerCase();
              const marcaLower = fabricante.toLowerCase();
              
              if (marcaLower.includes('honda') || marcaLower.includes('yamaha') || 
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
              anoModelo: this.onlyDigits(String(anoMod)).slice(0, 4),
              tipoVeiculo: tipoVeiculo
          };
      }
      
      async validatePlaca(placa) {
          const raw = placa.toUpperCase().replace(/[^A-Z0-9]/g, '');
          if (!this.validarPlacaFormato(raw)) {
              return { ok: false, reason: 'formato' };
          }
          
          try {
              const response = await fetch('https://mdmidia.com.br/placa-validate.php', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({
                      placa: raw
                  })
              });
              
              const data = await response.json();
              const ok = !!data && (data.codigo === 1 || data.success === true);
              
              return {
                  ok,
                  reason: ok ? 'ok' : 'nao_encontrada',
                  parsed: ok ? this.extractVehicleFromPlacaFipe(data) : { marcaTxt: '', anoModelo: '', tipoVeiculo: '' }
              };
          } catch (error) {
              return { ok: false, reason: 'erro_api' };
          }
      }
      
      // Celular Validation
      validarCelularLocal(ddd, numero) {
          const d = this.onlyDigits(ddd), n = this.onlyDigits(numero);
          if (d.length !== 2) return { ok: false, reason: 'ddd' };
          if (n.length !== 9) return { ok: false, reason: 'len' };
          if (n[0] !== '9') return { ok: false, reason: 'pattern' };
          return { ok: true, national: d + n };
      }
      
      async validarCelularApi(nat) {
          if (!this.config.USE_PHONE_API) return { ok: true };
          
          try {
              const response = await fetch(`https://apilayer.net/api/validate?access_key=${this.config.APILAYER_KEY}&country_code=BR&number=${nat}`);
              const data = await response.json();
              return { ok: !!data?.valid };
          } catch (error) {
              return { ok: true }; // falha externa não bloqueia
          }
      }
      
      async validateCelular(ddd, celular) {
          const local = this.validarCelularLocal(ddd, celular);
          if (!local.ok) return { ok: false, reason: local.reason };
          
          const api = await this.validarCelularApi(local.national);
          return { ok: api.ok };
      }
      
      // Email Validation
      validarEmailLocal(v) {
          return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i.test((v || '').trim());
      }
      
      async validateEmail(email) {
          if (!email) return { ok: false, reason: 'vazio' };
          
          const localOk = this.validarEmailLocal(email);
          if (!localOk) return { ok: false, reason: 'formato' };
          
          // SafetyMails check (não bloqueante)
          try {
              const response = await fetch(this.config.SAFETY_BASE + btoa(email));
              const data = await response.json();
              if (data && data.StatusEmail && data.StatusEmail !== 'VALIDO') {
                  return { ok: false, reason: 'safety_mails' };
              }
          } catch (error) {
              // Silêncio em erro externo
          }
          
          return { ok: true };
      }
  }
  
  // ========================================
  // 4. CLASSE PRINCIPAL DA PÁGINA
  // ========================================
  
  class MainPage {
      constructor() {
          this.sessionId = null;
          this.modalProgress = null;
          
          // Dados fixos (hardcoded)
          this.fixedData = {
              telefone: "11999999999",
              email: "cliente@exemplo.com",
              profissao: "Empresário",
              renda_mensal: "10000",
              modelo: "Civic",
              ano: "2020",
              cor: "Prata",
              combustivel: "Flex",
              zero_km: "false",
              uso: "Particular",
              garagem: "true",
              tipo_seguro: "Comprehensive",
              franquia: "500",
              cobertura_terceiros: "true",
              cobertura_vidros: "true",
              cobertura_carro_reserva: "true",
              cobertura_assistencia: "true"
          };
          
          this.init();
      }
      
      init() {
          console.log('🚀 MainPage inicializada');
          this.setupEventListeners();
      }
      
      setupEventListeners() {
          // Aguardar o DOM estar pronto
          if (document.readyState === 'loading') {
              document.addEventListener('DOMContentLoaded', () => {
                  this.setupFormSubmission();
              });
          } else {
              this.setupFormSubmission();
          }
      }
      
      setupFormSubmission() {
          // Procurar por formulário no Webflow
          const forms = document.querySelectorAll('form');
          console.log('📋 Formulários encontrados:', forms.length);
          
          // Interceptar botão específico do Webflow
          const submitButton = document.getElementById('submit_button_auto');
          if (submitButton) {
              console.log('🎯 Botão submit_button_auto encontrado');
              
              submitButton.addEventListener('click', (e) => {
                  e.preventDefault();
                  e.stopPropagation();
                  console.log('🎯 Botão CALCULE AGORA! clicado');
                  
                  // Encontrar o formulário pai
                  const form = submitButton.closest('form');
                  if (form) {
                      console.log('📋 Formulário encontrado via botão');
                      this.handleFormSubmit(form);
                  } else {
                      console.error('❌ Formulário não encontrado');
                  }
              });
          }
          
          // Fallback: interceptar submit do formulário
          forms.forEach((form, index) => {
              console.log(`📋 Configurando formulário ${index + 1}`);
              
              form.addEventListener('submit', (e) => {
                  e.preventDefault();
                  console.log('📋 Formulário submetido:', form);
                  this.handleFormSubmit(form);
              });
          });
      }
      
      collectFormData(form) {
          const formData = new FormData(form);
          const data = {};
          
          // Coletar dados do formulário
          for (let [key, value] of formData.entries()) {
              data[key] = value;
          }
          
          // ✅ CORREÇÃO: Capturar campo GCLID_FLD manualmente
          const gclidField = document.getElementById('GCLID_FLD');
          if (gclidField) {
              data.GCLID_FLD = gclidField.value || 'TesteRPA123';
              console.log('✅ Campo GCLID_FLD capturado:', data.GCLID_FLD);
          } else {
              data.GCLID_FLD = 'TesteRPA123'; // Valor padrão
              console.log('⚠️ Campo GCLID_FLD não encontrado, usando valor padrão');
          }
          
          // Aplicar conversões específicas
          this.applyFieldConversions(data);
          
          // Remover campos duplicados incorretos (maiúsculos) antes de mesclar
          const cleanedData = this.removeDuplicateFields(data);
          
          // Mesclar com dados fixos
          const completeData = { ...this.fixedData, ...cleanedData };
          
          console.log('📊 Dados coletados:', completeData);
          return completeData;
      }
      
      /**
       * Remove campos duplicados incorretos (maiúsculos) mantendo apenas os corretos (minúsculos)
       * @param {Object} data - Dados do formulário
       * @returns {Object} - Dados limpos sem duplicatas
       */
      removeDuplicateFields(data) {
          const cleanedData = { ...data };
          
          // Lista de campos que devem ser removidos (versões maiúsculas incorretas)
          const fieldsToRemove = [
              'DATA-DE-NASCIMENTO',  // Manter apenas 'data_nascimento'
              'SEXO',               // Manter apenas 'sexo'
              'ESTADO-CIVIL',       // Manter apenas 'estado_civil'
              'DDD-CELULAR',        // Manter apenas 'telefone' (concatenado)
              'CELULAR',            // Manter apenas 'telefone' (concatenado)
              'PLACA',              // Manter apenas 'placa'
              'MARCA',              // Manter apenas 'marca'
              'ANO',                // Manter apenas 'ano'
              'TIPO-DE-VEICULO',    // Manter apenas 'tipo_veiculo'
              'CEP',                // Manter apenas 'cep'
              'CPF'                 // Manter apenas 'cpf'
          ];
          
          // Remover campos duplicados incorretos
          fieldsToRemove.forEach(field => {
              if (cleanedData[field]) {
                  console.log(`🗑️ Removendo campo duplicado incorreto: ${field}`);
                  delete cleanedData[field];
              }
          });
          
          console.log('🧹 Campos duplicados removidos. Campos restantes:', Object.keys(cleanedData));
          return cleanedData;
      }
      
      /**
       * Aplica conversões específicas nos campos do formulário
       * @param {Object} data - Dados do formulário
       */
      applyFieldConversions(data) {
          // Converter estado civil
          if (data['ESTADO-CIVIL']) {
              data.estado_civil = this.convertEstadoCivil(data['ESTADO-CIVIL']);
              console.log(`🔄 Estado civil convertido: "${data['ESTADO-CIVIL']}" → "${data.estado_civil}"`);
          }
          
          // Converter sexo
          if (data.SEXO) {
              data.sexo = this.convertSexo(data.SEXO);
              console.log(`🔄 Sexo convertido: "${data.SEXO}" → "${data.sexo}"`);
          }
          
          // Converter tipo de veículo
          if (data['TIPO-DE-VEICULO']) {
              data.tipo_veiculo = this.convertTipoVeiculo(data['TIPO-DE-VEICULO']);
              console.log(`🔄 Tipo de veículo convertido: "${data['TIPO-DE-VEICULO']}" → "${data.tipo_veiculo}"`);
          }
          
          // Concatenar DDD + CELULAR (APENAS se não existir telefone fixo)
          if (data['DDD-CELULAR'] && data.CELULAR && !data.telefone) {
              data.telefone = data['DDD-CELULAR'] + data.CELULAR;
              console.log(`🔄 Telefone concatenado: "${data['DDD-CELULAR']}" + "${data.CELULAR}" = "${data.telefone}"`);
          }
          
          // Mapear campos do Webflow para nomes do RPA (EXCLUINDO CAMPOS FIXOS)
          const fieldMapping = {
              'CPF': 'cpf',
              'PLACA': 'placa',
              'MARCA': 'marca',
              'CEP': 'cep',
              'DATA-DE-NASCIMENTO': 'data_nascimento'
              // REMOVIDO: 'TIPO-DE-VEICULO' (convertido separadamente)
          };
          
          // Aplicar mapeamento de campos
          Object.keys(fieldMapping).forEach(webflowField => {
              if (data[webflowField]) {
                  data[fieldMapping[webflowField]] = data[webflowField];
              }
          });
          
          // Converter tipo de veículo após o mapeamento
          if (data['TIPO-DE-VEICULO']) {
              data.tipo_veiculo = this.convertTipoVeiculo(data['TIPO-DE-VEICULO']);
              console.log(`🔄 Tipo de veículo convertido: "${data['TIPO-DE-VEICULO']}" → "${data.tipo_veiculo}"`);
          }
      }
      
      /**
       * Converte valores de estado civil do Webflow para o domínio do RPA
       * @param {string} webflowValue - Valor vindo do Webflow
       * @returns {string} - Valor convertido para o RPA
       */
      convertEstadoCivil(webflowValue) {
          if (!webflowValue) return '';
          
          // Normalizar entrada (minúsculas, sem acentos, sem espaços extras)
          const normalized = webflowValue
              .toLowerCase()
              .trim()
              .replace(/[àáâãäå]/g, 'a')
              .replace(/[èéêë]/g, 'e')
              .replace(/[ìíîï]/g, 'i')
              .replace(/[òóôõö]/g, 'o')
              .replace(/[ùúûü]/g, 'u')
              .replace(/[ç]/g, 'c')
              .replace(/[ñ]/g, 'n')
              .replace(/[^a-z0-9\s]/g, '')
              .replace(/\s+/g, ' ');
          
          console.log(`🔍 Estado civil normalizado: "${webflowValue}" → "${normalized}"`);
          
          // Mapeamento robusto com múltiplas variações
          const estadoCivilMapping = {
              // Solteiro
              'solteiro': 'Solteiro',
              'sol': 'Solteiro',
              'single': 'Solteiro',
              'solteira': 'Solteiro',
              
              // Casado
              'casado': 'Casado',
              'cas': 'Casado',
              'married': 'Casado',
              'casada': 'Casado',
              
              // Casado ou União Estável
              'casado ou uniao estavel': 'Casado ou Uniao Estavel',
              'casado ou uniao': 'Casado ou Uniao Estavel',
              'casado uniao estavel': 'Casado ou Uniao Estavel',
              'casado uniao': 'Casado ou Uniao Estavel',
              'uniao estavel': 'Casado ou Uniao Estavel',
              'uniao': 'Casado ou Uniao Estavel',
              'casado ou união estável': 'Casado ou Uniao Estavel',
              'casado ou união': 'Casado ou Uniao Estavel',
              'casado união estável': 'Casado ou Uniao Estavel',
              'casado união': 'Casado ou Uniao Estavel',
              'união estável': 'Casado ou Uniao Estavel',
              'união': 'Casado ou Uniao Estavel',
              
              // Divorciado
              'divorciado': 'Divorciado',
              'div': 'Divorciado',
              'divorced': 'Divorciado',
              'divorciada': 'Divorciado',
              
              // Separado
              'separado': 'Separado',
              'sep': 'Separado',
              'separated': 'Separado',
              'separada': 'Separado',
              
              // Viúvo
              'viuvo': 'Viuvo',
              'viuva': 'Viuvo',
              'widowed': 'Viuvo',
              'viúvo': 'Viuvo',
              'viúva': 'Viuvo'
          };
          
          // Buscar correspondência exata
          if (estadoCivilMapping[normalized]) {
              return estadoCivilMapping[normalized];
          }
          
          // Buscar correspondência parcial (para casos com variações)
          for (const [key, value] of Object.entries(estadoCivilMapping)) {
              if (normalized.includes(key) || key.includes(normalized)) {
                  console.log(`🔍 Correspondência parcial encontrada: "${normalized}" → "${value}"`);
                  return value;
              }
          }
          
          // Fallback: retornar valor original capitalizado
          console.warn(`⚠️ Estado civil não mapeado: "${webflowValue}". Usando fallback.`);
          return webflowValue.charAt(0).toUpperCase() + webflowValue.slice(1).toLowerCase();
      }
      
      /**
       * Converte valores de sexo do Webflow para o domínio do RPA
       * @param {string} webflowValue - Valor vindo do Webflow
       * @returns {string} - Valor convertido para o RPA
       */
      convertSexo(webflowValue) {
          if (!webflowValue) return '';
          
          // Normalizar entrada (minúsculas, sem espaços extras)
          const normalized = webflowValue.toLowerCase().trim();
          
          console.log(`🔍 Sexo normalizado: "${webflowValue}" → "${normalized}"`);
          
          // Mapeamento robusto com múltiplas variações
          const sexoMapping = {
              // Masculino
              'm': 'Masculino',
              'masculino': 'Masculino',
              'male': 'Masculino',
              'masculina': 'Masculino',
              'masculino': 'Masculino',
              'mas': 'Masculino',
              'mascul': 'Masculino',
              
              // Feminino
              'f': 'Feminino',
              'feminino': 'Feminino',
              'female': 'Feminino',
              'feminina': 'Feminino',
              'feminino': 'Feminino',
              'fem': 'Feminino',
              'femin': 'Feminino'
          };
          
          // Buscar correspondência exata
          if (sexoMapping[normalized]) {
              return sexoMapping[normalized];
          }
          
          // Buscar correspondência parcial (para casos com variações)
          for (const [key, value] of Object.entries(sexoMapping)) {
              if (normalized.includes(key) || key.includes(normalized)) {
                  console.log(`🔍 Correspondência parcial encontrada: "${normalized}" → "${value}"`);
                  return value;
              }
          }
          
          // Fallback: retornar valor original capitalizado
          console.warn(`⚠️ Sexo não mapeado: "${webflowValue}". Usando fallback.`);
          return webflowValue.charAt(0).toUpperCase() + webflowValue.slice(1).toLowerCase();
      }
      
      /**
       * Converter tipo de veículo para formato do RPA
       * @param {string} webflowValue - Valor do Webflow
       * @returns {string} - Valor convertido para o RPA
       */
      convertTipoVeiculo(webflowValue) {
          if (!webflowValue) return 'carro';
          
          // Normalizar entrada (minúsculas, sem espaços extras)
          const tipo = webflowValue.toLowerCase().trim();
          
          console.log(`🔍 Tipo de veículo normalizado: "${webflowValue}" → "${tipo}"`);
          
          // Mapear tipos de carro para "carro"
          const tiposCarro = ['sedan', 'hatch', 'hatchback', 'suv', 'pickup', 'crossover', 'wagon', 'coupe', 'convertible', 'carro', 'automovel'];
          
          // Mapear tipos de moto para "moto"
          const tiposMoto = ['moto', 'motocicleta', 'scooter', 'bike', 'motoneta'];
          
          // Verificar se é carro
          for (const tipoCarro of tiposCarro) {
              if (tipo.includes(tipoCarro) || tipoCarro.includes(tipo)) {
                  console.log(`🔍 Tipo de veículo reconhecido como carro: "${tipo}" → "carro"`);
                  return 'carro';
              }
          }
          
          // Verificar se é moto
          for (const tipoMoto of tiposMoto) {
              if (tipo.includes(tipoMoto) || tipoMoto.includes(tipo)) {
                  console.log(`🔍 Tipo de veículo reconhecido como moto: "${tipo}" → "moto"`);
                  return 'moto';
              }
          }
          
          // Default para carro se não reconhecer
          console.warn(`⚠️ Tipo de veículo não reconhecido: "${webflowValue}" - usando "carro" como padrão`);
          return 'carro';
      }
      
      async handleFormSubmit(form) {
          try {
              console.log('🚀 Iniciando processo RPA...');
              
              // Atualizar texto do botão para loading
              this.updateButtonLoading(true);
              
              // Coletar dados do formulário
              const formData = this.collectFormData(form);
              
              // VALIDAÇÃO COMPLETA (NOVO)
              console.log('🔍 Iniciando validação de formulário...');
              const validationResult = await this.validateFormData(formData);
              
              // Se inválido, mostrar SweetAlert
              if (!validationResult.isValid) {
                  console.log('❌ Validação falhou:', validationResult.errors);
                  await this.showValidationAlert(validationResult.errors);
                  this.updateButtonLoading(false);
                  return; // NÃO executar RPA
              }
              
              console.log('✅ Validação passou, executando RPA...');
              
              // Armazenar telefone globalmente para WhatsApp
              window.rpaData = { telefone: formData.telefone };
              
              // Executar webhooks do Webflow ANTES do RPA
              // await this.executeWebflowWebhooks(form, formData); // COMENTADO PARA TESTES
              
              // Abrir modal de progresso (apenas HTML)
              this.openProgressModal();
              
              // Chamar API RPA
              console.log('📤 JSON sendo enviado para API:', JSON.stringify(formData, null, 2));
              console.log('📤 Campos do formulário:', Object.keys(formData));
              console.log('📤 Valores dos campos:', formData);
              
              const response = await fetch('https://rpaimediatoseguros.com.br/api/rpa/start', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                  },
                  body: JSON.stringify(formData)
              });
              
              const result = await response.json();
              console.log('📊 Resposta da API:', result);
              console.log('📊 Status da resposta:', response.status);
              console.log('📊 Headers da resposta:', response.headers);
              
              if (result.success && result.session_id) {
                  this.sessionId = result.session_id;
                  console.log('✅ Session ID recebido:', this.sessionId);
                  
                  // Inicializar modal de progresso COM SESSION ID
                  this.initializeProgressModal();
              } else {
                  console.error('❌ Erro na API:', result);
                  this.updateButtonLoading(false);
                  this.showError('Erro ao iniciar o cálculo. Tente novamente.');
              }
              
          } catch (error) {
              console.error('❌ Erro no processo:', error);
              this.updateButtonLoading(false);
              this.showError('Erro de conexão. Verifique sua internet e tente novamente.');
          }
      }
      
      /**
       * Inicializar modal de progresso COM SESSION ID
       */
      initializeProgressModal() {
          console.log('🔄 Inicializando modal de progresso...');
          
          // Aguardar um pouco para garantir que o modal esteja no DOM
          setTimeout(() => {
              if (window.ProgressModalRPA) {
                  this.modalProgress = new window.ProgressModalRPA(this.sessionId);
                  // ✅ CORREÇÃO: Atribuir à variável global para debug
                  window.progressModal = this.modalProgress;
                  this.modalProgress.startProgressPolling();
                  console.log('✅ Modal de progresso inicializado');
              } else {
                  console.error('❌ ProgressModalRPA não encontrado');
                  console.log('🔍 Classes disponíveis:', Object.keys(window).filter(key => key.includes('Modal')));
              }
          }, 200);
      }
      
      /**
       * Validar dados do formulário completo
       */
      async validateFormData(formData) {
          const validator = new FormValidator();
          
          console.log('🔍 Validando dados:', formData);
          
          // Executar todas as validações em paralelo
          const [cpfResult, cepResult, placaResult, celularResult, emailResult] = await Promise.all([
              validator.validateCPF(formData.cpf),
              validator.validateCEP(formData.cep),
              validator.validatePlaca(formData.placa),
              validator.validateCelular(formData.ddd_celular, formData.celular),
              validator.validateEmail(formData.email)
          ]);
          
          console.log('📊 Resultados da validação:', {
              cpf: cpfResult,
              cep: cepResult,
              placa: placaResult,
              celular: celularResult,
              email: emailResult
          });
          
          // Auto-preenchimento se válido
          if (placaResult.ok && placaResult.parsed) {
              console.log('🚗 Auto-preenchendo dados da placa:', placaResult.parsed);
              this.setFieldValue('MARCA', placaResult.parsed.marcaTxt);
              this.setFieldValue('ANO', placaResult.parsed.anoModelo);
              this.setFieldValue('TIPO-DE-VEICULO', placaResult.parsed.tipoVeiculo);
          }
          
          if (cpfResult.ok && cpfResult.parsed && validator.config.VALIDAR_PH3A) {
              console.log('👤 Auto-preenchendo dados do CPF:', cpfResult.parsed);
              this.setFieldValue('SEXO', cpfResult.parsed.sexo);
              this.setFieldValue('DATA-DE-NASCIMENTO', cpfResult.parsed.dataNascimento);
              this.setFieldValue('ESTADO-CIVIL', cpfResult.parsed.estadoCivil);
          }
          
          return {
              isValid: cpfResult.ok && cepResult.ok && placaResult.ok && celularResult.ok && emailResult.ok,
              errors: {
                  cpf: cpfResult,
                  cep: cepResult,
                  placa: placaResult,
                  celular: celularResult,
                  email: emailResult
              }
          };
      }
      
      /**
       * Mostrar SweetAlert de validação
       */
      async showValidationAlert(errors) {
          let errorLines = "";
          if (!errors.cpf.ok) errorLines += "• CPF inválido\n";
          if (!errors.cep.ok) errorLines += "• CEP inválido\n";
          if (!errors.placa.ok) errorLines += "• Placa inválida\n";
          if (!errors.celular.ok) errorLines += "• Celular inválido\n";
          if (!errors.email.ok) errorLines += "• E-mail inválido\n";
          
          console.log('🚨 Mostrando SweetAlert de validação:', errorLines);
          
          const result = await Swal.fire({
              icon: 'info',
              title: 'Atenção!',
              html: 
                  "⚠️ Os campos CPF, CEP, PLACA, CELULAR e E-MAIL corretamente preenchidos são necessários para efetuar o cálculo do seguro.\n\n" +
                  "Campos com problema:\n\n" + errorLines + "\n" +
                  "Caso decida prosseguir assim mesmo, um especialista entrará em contato para coletar esses dados.",
              showCancelButton: true,
              confirmButtonText: 'Prosseguir assim mesmo',
              cancelButtonText: 'Corrigir',
              reverseButtons: true,
              allowOutsideClick: false,
              allowEscapeKey: true
          });
          
          if (result.isConfirmed) {
              console.log('✅ Usuário escolheu prosseguir, redirecionando...');
              // NÃO executar RPA - redirecionar para página de sucesso
              window.location.href = 'https://www.segurosimediato.com.br/sucesso';
          } else {
              console.log('🔧 Usuário escolheu corrigir, focando campo com erro...');
              // Focar no primeiro campo com erro
              this.focusFirstErrorField(errors);
          }
      }
      
      /**
       * Focar no primeiro campo com erro
       */
      focusFirstErrorField(errors) {
          if (!errors.cpf.ok) {
              const cpfField = document.getElementById('CPF') || document.querySelector('[name="CPF"]');
              if (cpfField) { cpfField.focus(); return; }
          }
          if (!errors.cep.ok) {
              const cepField = document.getElementById('CEP') || document.querySelector('[name="CEP"]');
              if (cepField) { cepField.focus(); return; }
          }
          if (!errors.placa.ok) {
              const placaField = document.getElementById('PLACA') || document.querySelector('[name="PLACA"]');
              if (placaField) { placaField.focus(); return; }
          }
          if (!errors.celular.ok) {
              const celularField = document.getElementById('CELULAR') || document.querySelector('[name="CELULAR"]');
              if (celularField) { celularField.focus(); return; }
          }
          if (!errors.email.ok) {
              const emailField = document.getElementById('email') || document.querySelector('[name="email"]');
              if (emailField) { emailField.focus(); return; }
          }
      }
      
      /**
       * Definir valor de campo (auto-preenchimento)
       */
      setFieldValue(id, val) {
          const field = document.getElementById(id) || document.querySelector(`[name="${id}"]`);
          if (field) {
              field.value = val;
              field.dispatchEvent(new Event('input', { bubbles: true }));
              field.dispatchEvent(new Event('change', { bubbles: true }));
              console.log(`✅ Campo ${id} preenchido com: ${val}`);
          } else {
              console.warn(`⚠️ Campo ${id} não encontrado`);
          }
      }
      
      /**
       * Mostrar erro personalizado
       */
      showError(message) {
          // Remover modal existente se houver
          const existingModal = document.getElementById('rpaModal');
          if (existingModal) {
              existingModal.remove();
          }
          
          // Mostrar erro
          alert(message);
          
          // Restaurar botão
          this.updateButtonLoading(false);
      }
      
      /**
       * Executa os webhooks do Webflow manualmente
       * @param {HTMLFormElement} form - Formulário
       * @param {Object} formData - Dados do formulário
       * 
       * COMENTADO PARA TESTES - DESCOMENTAR QUANDO FOR PARA WEBFLOW
       */
      /*
      async executeWebflowWebhooks(form, formData) {
          console.log('🔗 Executando webhooks do Webflow...');
          
          try {
              // Webhook 1: Send form data to Webflow
              await this.sendToWebflow(formData);
              
              // Webhook 2: webhook.site
              await this.sendToWebhookSite(formData);
              
              // Webhook 3: mdmidia.com.br/add_tra
              await this.sendToMdmidiaTra(formData);
              
              // Webhook 4: mdmidia.com.br/add_we
              await this.sendToMdmidiaWe(formData);
              
              console.log('✅ Todos os webhooks executados com sucesso');
              
          } catch (error) {
              console.warn('⚠️ Erro ao executar webhooks:', error);
              // Não bloquear o processo por erro nos webhooks
          }
      }
      
      async sendToWebflow(formData) {
          try {
              const response = await fetch(form.action, {
                  method: 'POST',
                  body: new FormData(form)
              });
              console.log('📤 Webflow webhook executado');
          } catch (error) {
              console.warn('⚠️ Erro no webhook Webflow:', error);
          }
      }
      
      async sendToWebhookSite(formData) {
          try {
              const response = await fetch('https://webhook.site/6431c548...', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                  },
                  body: JSON.stringify(formData)
              });
              console.log('📤 Webhook.site executado');
          } catch (error) {
              console.warn('⚠️ Erro no webhook.site:', error);
          }
      }
      
      async sendToMdmidiaTra(formData) {
          try {
              const response = await fetch('https://mdmidia.com.br/add_tra...', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                  },
                  body: JSON.stringify(formData)
              });
              console.log('📤 Mdmidia TRA executado');
          } catch (error) {
              console.warn('⚠️ Erro no webhook Mdmidia TRA:', error);
          }
      }
      
      async sendToMdmidiaWe(formData) {
          try {
              const response = await fetch('https://mdmidia.com.br/add_we...', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                  },
                  body: JSON.stringify(formData)
              });
              console.log('📤 Mdmidia WE executado');
          } catch (error) {
              console.warn('⚠️ Erro no webhook Mdmidia WE:', error);
          }
      }
      */
      
      /**
       * Atualiza o estado de loading do botão
       * @param {boolean} isLoading - Se está carregando
       */
      updateButtonLoading(isLoading) {
          const submitButton = document.getElementById('submit_button_auto');
          if (submitButton) {
              if (isLoading) {
                  submitButton.textContent = 'Aguarde...';
                  submitButton.disabled = true;
              } else {
                  submitButton.textContent = 'CALCULE AGORA!';
                  submitButton.disabled = false;
              }
          }
      }
      
      /**
       * Garantir que Font Awesome está carregado
       */
      ensureFontAwesomeLoaded() {
          return new Promise((resolve) => {
              // Verificar se Font Awesome já está carregado
              const existingFA = document.querySelector('link[href*="font-awesome"]') || 
                               document.querySelector('link[href*="fontawesome"]') ||
                               document.querySelector('link[href*="font-awesome"]');
              
              if (existingFA) {
                  console.log('🎨 Font Awesome já carregado');
                  // Aguardar um pouco para garantir que os estilos foram aplicados
                  setTimeout(() => {
                      console.log('🎨 Font Awesome verificado e pronto');
                      resolve();
                  }, 100);
                  return;
              }
              
              console.log('🎨 Carregando Font Awesome...');
              const fontAwesome = document.createElement('link');
              fontAwesome.rel = 'stylesheet';
              fontAwesome.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css';
              fontAwesome.crossOrigin = 'anonymous';
              
              fontAwesome.onload = () => {
                  console.log('🎨 Font Awesome carregado com sucesso');
                  // Aguardar um pouco para garantir que os estilos foram aplicados
                  setTimeout(() => {
                      console.log('🎨 Font Awesome aplicado e pronto');
                      resolve();
                  }, 200);
              };
              
              fontAwesome.onerror = () => {
                  console.warn('⚠️ Erro ao carregar Font Awesome');
                  resolve();
              };
              
              document.head.appendChild(fontAwesome);
          });
      }
      
      openProgressModal() {
          console.log('🎭 Criando modal de progresso...');
          
          // Garantir que Font Awesome está carregado
          this.ensureFontAwesomeLoaded();
          
          // Remover modal existente se houver
          const existingModal = document.getElementById('rpaModal');
          if (existingModal) {
              existingModal.remove();
          }
          
          // Criar modal HTML
          const modalHTML = `
              <div id="rpaModal" class="show">
                  <button class="modal-close-btn" onclick="document.getElementById('rpaModal').remove()">
                      <i class="fas fa-times"></i>
                  </button>
                  <div class="modal-progress-bar">
                      <div class="progress-header">
                          <div class="logo-container">
                              <img src="https://cdn.prod.website-files.com/59eb807f9d16950001e202af/5f845624fe08f9f0d0573fee_logotipo-imediato-seguros.svg" alt="Imediato Seguros" class="company-logo">
                          </div>
                          <h1><i class="fas fa-calculator"></i> Calculadora de Seguro da Imediato Seguros</h1>
                          <div class="progress-info">
                              <span class="progress-text" id="progressText">0%</span>
                              <span class="current-phase" id="currentPhase">Iniciando Multi-Cálculo...</span>
                              <span class="sub-phase" id="subPhase"></span>
                          </div>
                          <div class="progress-stages">
                              <span class="stage-info" id="stageInfo">Fase 0 de 16</span>
                          </div>
                      </div>
                      
                      <div class="progress-bar-wrapper">
                          <div class="progress-bar-container">
                              <div class="progress-bar-fill" id="progressBarFill" style="width: 0%"></div>
                          </div>
                      </div>
                  </div>
                  
                  <div class="modal-content">
                      <div class="results-container">
                          <!-- Div 1: Recomendado -->
                          <div class="result-card recommended">
                              <div class="card-header">
                                  <div class="card-icon">
                                      <i class="fas fa-star"></i>
                                  </div>
                                  <div class="card-title">
                                      <h3>Recomendado</h3>
                                  </div>
                                  <div class="card-value-inline">
                                      <div class="value" id="recommendedValue">R$ 0,00</div>
                                  </div>
                              </div>
                              <ul class="card-features">
                                  <li>Forma de Pagamento: <span class="feature-value" id="recommendedFormaPagamento">-</span></li>
                                  <li>Parcelamento: <span class="feature-value" id="recommendedParcelamento">-</span></li>
                                  <li>Valor de Mercado: <span class="feature-value" id="recommendedValorMercado">-</span></li>
                                  <li>Valor da Franquia: <span class="feature-value" id="recommendedValorFranquia">-</span></li>
                                  <li>Tipo de Franquia: <span class="feature-value" id="recommendedTipoFranquia">-</span></li>
                                  <li>Cobertura de Assistência: <span class="feature-value" id="recommendedAssistencia">-</span></li>
                                  <li>Cobertura de Vidros: <span class="feature-value" id="recommendedVidros">-</span></li>
                                  <li>Cobertura de Carro Reserva: <span class="feature-value" id="recommendedCarroReserva">-</span></li>
                                  <li>Cobertura de Danos Materiais: <span class="feature-value" id="recommendedDanosMateriais">-</span></li>
                                  <li>Cobertura de Danos Corporais: <span class="feature-value" id="recommendedDanosCorporais">-</span></li>
                                  <li>Cobertura de Danos Morais: <span class="feature-value" id="recommendedDanosMorais">-</span></li>
                                  <li>Cobertura de Morte e Invalidez Permanente: <span class="feature-value" id="recommendedMorteInvalidez">-</span></li>
                              </ul>
                          </div>
                          
                          <!-- Div 2: Alternativo -->
                          <div class="result-card alternative">
                              <div class="card-header">
                                  <div class="card-icon">
                                      <i class="fas fa-shield-alt"></i>
                                  </div>
                                  <div class="card-title">
                                      <h3>Alternativo</h3>
                                  </div>
                                  <div class="card-value-inline">
                                      <div class="value" id="alternativeValue">R$ 0,00</div>
                                  </div>
                              </div>
                              <ul class="card-features">
                                  <li>Forma de Pagamento: <span class="feature-value" id="alternativeFormaPagamento">-</span></li>
                                  <li>Parcelamento: <span class="feature-value" id="alternativeParcelamento">-</span></li>
                                  <li>Valor de Mercado: <span class="feature-value" id="alternativeValorMercado">-</span></li>
                                  <li>Valor da Franquia: <span class="feature-value" id="alternativeValorFranquia">-</span></li>
                                  <li>Tipo de Franquia: <span class="feature-value" id="alternativeTipoFranquia">-</span></li>
                                  <li>Cobertura de Assistência: <span class="feature-value" id="alternativeAssistencia">-</span></li>
                                  <li>Cobertura de Vidros: <span class="feature-value" id="alternativeVidros">-</span></li>
                                  <li>Cobertura de Carro Reserva: <span class="feature-value" id="alternativeCarroReserva">-</span></li>
                                  <li>Cobertura de Danos Materiais: <span class="feature-value" id="alternativeDanosMateriais">-</span></li>
                                  <li>Cobertura de Danos Corporais: <span class="feature-value" id="alternativeDanosCorporais">-</span></li>
                                  <li>Cobertura de Danos Morais: <span class="feature-value" id="alternativeDanosMorais">-</span></li>
                                  <li>Cobertura de Morte e Invalidez Permanente: <span class="feature-value" id="alternativeMorteInvalidez">-</span></li>
                              </ul>
                          </div>
                      </div>
                      
                      <!-- Spinner com Timer Regressivo -->
                      <div class="spinner-timer-container" id="spinnerTimerContainer" style="display: none;">
                          <div class="spinner-container">
                              <div class="sk-circle" id="skCircle">
                                  <div class="sk-child"></div>
                                  <div class="sk-child"></div>
                                  <div class="sk-child"></div>
                                  <div class="sk-child"></div>
                                  <div class="sk-child"></div>
                                  <div class="sk-child"></div>
                                  <div class="sk-child"></div>
                                  <div class="sk-child"></div>
                                  <div class="sk-child"></div>
                                  <div class="sk-child"></div>
                                  <div class="sk-child"></div>
                                  <div class="sk-child"></div>
                              </div>
                              <div class="spinner-center" id="spinnerCenter">03:00</div>
                          </div>
                          <div class="timer-message" id="timerMessage" style="display: none;">
                              ⏰ Está demorando mais que o normal. Aguardando mais 2 minutos...
                          </div>
                      </div>
                  </div>
              </div>
          `;
          
          // Injetar modal no DOM
          document.body.insertAdjacentHTML('beforeend', modalHTML);
          
          // Mostrar spinner timer após 2 segundos
          setTimeout(() => {
              const spinnerContainer = document.getElementById('spinnerTimerContainer');
              if (spinnerContainer) {
                  spinnerContainer.style.display = 'flex';
                  console.log('✅ Spinner timer container exibido');
                  // ✅ MUDANÇA 9: Apenas mostra o container, não inicializa o timer
                  // O timer será inicializado pelo ProgressModalRPA.initSpinnerTimer()
              } else {
                  console.warn('⚠️ spinnerTimerContainer não encontrado');
              }
          }, 2000);
          
          // Modal será inicializado posteriormente com Session ID
          console.log('🎭 Modal HTML criado - aguardando Session ID...');
      }
  }
  
  // ========================================
  // 4. INICIALIZAÇÃO
  // ========================================
  
  // Expor classes globalmente
  window.SpinnerTimer = SpinnerTimer;
  window.ProgressModalRPA = ProgressModalRPA;
  window.MainPage = MainPage;
  
  // Injetar CSS
  const styleSheet = document.createElement('style');
  styleSheet.textContent = cssStyles;
  document.head.appendChild(styleSheet);
  
  // Aguardar Font Awesome carregar
  if (!document.querySelector('link[href*="font-awesome"]') && !document.querySelector('link[href*="fontawesome"]')) {
      const fontAwesome = document.createElement('link');
      fontAwesome.rel = 'stylesheet';
      fontAwesome.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css';
      fontAwesome.crossOrigin = 'anonymous';
      document.head.appendChild(fontAwesome);
      console.log('🎨 Font Awesome carregado');
  }
  
  // Inicializar aplicação
  const mainPage = new MainPage();
  
  console.log('🚀 Webflow Injection Complete V6.13.0 carregado com sucesso!');
  console.log('📋 SpinnerTimer integrado com ciclo de vida do RPA');
  console.log('📋 Validação completa de formulário implementada');
  console.log('📋 SweetAlert com opção "Prosseguir assim mesmo"');
  console.log('📋 Auto-preenchimento de campos (MARCA/ANO/TIPO)');
  console.log('📋 Redirecionamento para página de sucesso em caso de dados inválidos');
  
})();


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

    // Se DDD=2 e celular 1–8 dígitos → alerta imediato (incompleto)
    if (dddDigits === 2 && celDigits > 0 && celDigits < 9){
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

  // SUBMIT — COMENTADO PARA PROJETO RPA
  /* 
    MOTIVO: Validação de submit agora é implementada no new_webflow-injection-complete.js
    para integração completa com o RPA e evitar conflitos de interceptação
    
    IMPACTO: As validações individuais (CPF, CEP, Placa, Celular, Email) continuam
    funcionando em tempo real, mas a validação final no submit é feita pelo RPA
    
    COMPORTAMENTO: O RPA JavaScript intercepta o submit e executa sua própria validação
    completa antes de executar o processo automatizado
  */
  /*
  $('form').each(function(){
    const $form=$(this);
    $form.on('submit', function(ev){
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
          $form.data('validated-ok', true);
          nativeSubmit($form);
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
              $form.data('skip-validate', true);
              nativeSubmit($form);
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
          if (r.isConfirmed) { $form.data('skip-validate', true); nativeSubmit($form); }
        });
      });
    });
  });
  */
});
</script>
<!-- ====================== -->

<!-- ====================== -->
<!-- CONTADOR DE EQUIPES - MANTIDO -->
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