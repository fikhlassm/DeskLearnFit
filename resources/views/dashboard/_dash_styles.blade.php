{{-- Shared dashboard styles included by @include --}}
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
.dash-page{display:flex;min-height:100vh;font-family:'Plus Jakarta Sans',sans-serif;background:#F1F5F9;color:#0F172A;}
.dash-main{flex:1;display:flex;flex-direction:column;padding:1.5rem 2rem;gap:1.25rem;overflow-x:hidden;}
.topbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;}
.topbar__title{font-size:1.5rem;font-weight:800;color:#0F172A;letter-spacing:-.03em;}
.topbar__sub{font-size:.83rem;color:#64748B;margin-top:.1rem;}
.topbar__right{display:flex;align-items:center;gap:.6rem;}
.topbar__icon-btn{width:38px;height:38px;border:1px solid #E2E8F0;background:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .18s;}
.topbar__icon-btn:hover{background:#F1F5F9;}
.hamburger{display:none;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1px solid #E2E8F0;background:#fff;cursor:pointer;flex-shrink:0;}
.alert-success{background:#ECFDF5;border:1px solid #6EE7B7;border-radius:10px;padding:.65rem 1rem;color:#065F46;font-size:.83rem;}
.alert-error{background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:.65rem 1rem;color:#991B1B;font-size:.83rem;}
.empty-state{text-align:center;padding:2.5rem 1rem;background:#fff;border:1px dashed #E2E8F0;border-radius:16px;}
.empty-state__icon{font-size:2.5rem;margin-bottom:.65rem;}
.empty-state__title{font-size:.95rem;font-weight:700;color:#0F172A;margin-bottom:.3rem;}
.empty-state__sub{font-size:.8rem;color:#64748B;}
.sidebar-overlay{display:none;}
@media(max-width:900px){
.hamburger{display:flex;}
.sidebar{position:fixed;top:0;left:-260px;z-index:200;height:100vh;width:240px;transition:left .28s cubic-bezier(.4,0,.2,1);}
.sidebar.sidebar--open{left:0;box-shadow:4px 0 24px rgba(15,23,42,.15);}
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.35);z-index:199;transition:opacity .28s;opacity:0;}
.sidebar-overlay.overlay--show{display:block;opacity:1;}
.dash-main{padding:1rem;}
}
</style>
