<?php
session_start();

if (isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#070d20">
<meta name="description" content="FinIntel AI helps you explore loan eligibility, calculate EMIs, and understand financial insights.">
<title>FinIntel AI | Smart Loan Decisions</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
:root{--bg:#070d20;--panel:rgba(16,31,66,.78);--blue:#3987ff;--blue-light:#63c9ff;--purple:#7958ff;--mint:#50e3b0;--text:#f5f8ff;--muted:#a8b9d9;--border:rgba(157,190,255,.19);--container:1240px}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;scroll-padding-top:90px;background:var(--bg)}
body{min-height:100vh;position:relative;isolation:isolate;overflow-x:hidden;background:var(--bg);color:var(--text);font-family:Inter,sans-serif;-webkit-font-smoothing:antialiased;line-height:1.6}
a{color:inherit;text-decoration:none}button,input{font:inherit}button{cursor:pointer}
.container{width:min(var(--container),calc(100% - 64px));margin-inline:auto}
.section{position:relative;z-index:1;padding:90px 0}

/* Animated financial-tech background: no external photo */
.tech-background{position:fixed;inset:0;z-index:-1;overflow:hidden;pointer-events:none;background:var(--bg)}
.tech-background::before{content:"";position:absolute;inset:0;background:radial-gradient(ellipse at 76% 24%,rgba(28,104,255,.20),transparent 34%),radial-gradient(ellipse at 15% 62%,rgba(65,62,255,.14),transparent 38%),radial-gradient(ellipse at 80% 88%,rgba(117,57,255,.16),transparent 35%),linear-gradient(140deg,#071022 0%,#091632 52%,#090f29 100%)}
.tech-grid{position:absolute;inset:0;opacity:.20;background-image:linear-gradient(rgba(81,139,255,.13) 1px,transparent 1px),linear-gradient(90deg,rgba(81,139,255,.13) 1px,transparent 1px);background-size:54px 54px;mask-image:linear-gradient(to bottom,transparent 0%,black 25%,black 85%,transparent 100%)}
.data-wave{position:absolute;left:-10%;width:120%;height:250px;border-top:1px solid rgba(42,133,255,.34);border-radius:50%;transform:rotate(-8deg);filter:drop-shadow(0 0 14px rgba(36,117,255,.30));animation:waveFloat 14s ease-in-out infinite alternate}
.wave-one{top:34%}.wave-two{top:42%;transform:rotate(8deg);border-color:rgba(126,77,255,.28);animation-delay:-4s}.wave-three{top:78%;height:180px;transform:rotate(-4deg);border-color:rgba(34,189,255,.20);animation-delay:-7s}
.ambient-light{position:absolute;width:340px;height:340px;border-radius:50%;filter:blur(100px);opacity:.24;animation:lightDrift 16s ease-in-out infinite alternate}
.light-blue{background:#087dff;right:8%;top:12%}.light-purple{background:#7041ff;left:-150px;bottom:5%;animation-delay:-6s}
.data-points{position:absolute;inset:0;opacity:.3;background-image:radial-gradient(circle,#4d9bff 1px,transparent 1.7px);background-size:20px 20px;mask-image:linear-gradient(100deg,transparent 8%,black 70%,transparent)}
@keyframes waveFloat{from{transform:translateY(0) rotate(-8deg) scaleX(1)}to{transform:translateY(32px) rotate(-4deg) scaleX(1.04)}}
@keyframes lightDrift{from{transform:translate3d(0,0,0)}to{transform:translate3d(55px,-35px,0)}}
@keyframes fadeUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}

/* Navigation */
.navbar{position:sticky;top:0;z-index:1000;background:rgba(6,13,31,.83);border-bottom:1px solid rgba(155,186,255,.13);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px)}
.nav-inner{min-height:82px;display:flex;align-items:center;justify-content:space-between;gap:24px}
.brand{display:inline-flex;align-items:center;gap:11px;font-size:22px;font-weight:850;letter-spacing:-.8px;white-space:nowrap}
.brand-icon{display:grid;place-items:center;width:44px;height:44px;border-radius:14px;background:linear-gradient(140deg,#3e9dff,#7654ff);box-shadow:0 8px 26px rgba(59,122,255,.32);color:white}
.brand span span{color:#7db8ff}
.nav-links,.nav-actions{display:flex;align-items:center;gap:28px}
.nav-links a{color:#bac9e4;font-size:13px;font-weight:700;transition:color .2s}
.nav-links a:hover{color:white}
.btn{min-height:47px;display:inline-flex;align-items:center;justify-content:center;gap:10px;padding:0 20px;border-radius:12px;border:1px solid transparent;font-size:13px;font-weight:800;transition:transform .2s,box-shadow .2s,background .2s;white-space:nowrap}
.btn:hover{transform:translateY(-2px)}
.btn-primary{color:white;background:linear-gradient(110deg,#2687ff,#4b65ff);box-shadow:0 8px 25px rgba(43,111,255,.23)}
.btn-primary:hover{box-shadow:0 12px 30px rgba(43,111,255,.38)}
.btn-outline{color:#edf4ff;border-color:rgba(183,205,255,.36);background:rgba(255,255,255,.025)}
.btn-outline:hover{background:rgba(255,255,255,.08);border-color:rgba(134,178,255,.75)}
.menu-toggle{display:none;width:42px;height:42px;color:white;background:var(--panel);border:1px solid var(--border);border-radius:11px}

/* Hero */
.hero{position:relative;z-index:1;padding:78px 0 50px}
.hero-grid{display:grid;grid-template-columns:1.03fr .97fr;align-items:center;gap:54px}
.hero-copy{animation:fadeUp .7s ease both}
.eyebrow{display:inline-flex;align-items:center;gap:9px;padding:8px 14px;margin-bottom:23px;border:1px solid rgba(81,159,255,.44);border-radius:999px;background:rgba(28,92,183,.20);color:#bfdbff;font-size:11px;font-weight:800;letter-spacing:.9px;text-transform:uppercase}
.eyebrow i{color:#7ce8c3}
h1{max-width:700px;font-size:clamp(43px,5vw,68px);line-height:1.08;letter-spacing:-3.2px;font-weight:900}
.gradient-text{background:linear-gradient(100deg,#5bc7ff 0%,#4f86ff 48%,#a08aff 100%);color:transparent;background-clip:text;-webkit-background-clip:text}
.hero-description{max-width:600px;margin:23px 0 27px;color:#b2c3e1;font-size:16px;line-height:1.85}
.hero-buttons{display:flex;flex-wrap:wrap;gap:12px}.hero-buttons .btn{min-height:54px;padding:0 22px}
.trust-row{display:flex;flex-wrap:wrap;gap:22px;margin-top:25px;color:#c0cde3;font-size:12px}
.trust-row span{display:inline-flex;align-items:center;gap:8px}.trust-row i{color:var(--mint)}

/* Fintech visual built with CSS and icons */
.visual-stage{min-height:430px;position:relative;display:grid;place-items:center;animation:fadeUp .9s .1s ease both}
.orbit{position:absolute;width:360px;height:360px;border:1px solid rgba(72,146,255,.23);border-radius:50%;box-shadow:0 0 70px rgba(32,103,255,.08),inset 0 0 60px rgba(32,103,255,.04)}
.orbit::before,.orbit::after{content:"";position:absolute;inset:25px;border:1px dashed rgba(105,123,255,.25);border-radius:50%}.orbit::after{inset:55px;border-style:solid;border-color:rgba(60,193,255,.17)}
.bank-illustration{position:relative;z-index:2;width:225px;height:220px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:#8be0ff;filter:drop-shadow(0 0 25px rgba(45,132,255,.5))}
.bank-glow{position:absolute;inset:25px 0 0;z-index:-1;background:radial-gradient(ellipse,rgba(32,122,255,.28),transparent 68%)}
.bank-roof{width:170px;height:60px;display:grid;place-items:center;clip-path:polygon(50% 0,100% 75%,93% 100%,7% 100%,0 75%);background:linear-gradient(135deg,#6ce6ff,#477aff 60%,#946bff);color:#dff9ff;font-size:26px}
.bank-roof i{margin-top:12px}
.bank-columns{width:174px;height:90px;display:flex;justify-content:space-evenly;align-items:stretch;padding:8px 10px;background:linear-gradient(90deg,rgba(27,118,255,.38),rgba(97,75,255,.32));border-left:2px solid #50bfff;border-right:2px solid #7e83ff;border-bottom:4px solid #50bfff}
.bank-columns span{width:17px;border:2px solid rgba(139,224,255,.9);border-radius:5px 5px 1px 1px;background:linear-gradient(180deg,rgba(102,220,255,.4),rgba(51,91,255,.12))}
.bank-base{width:205px;height:15px;border:2px solid #62d9ff;background:linear-gradient(90deg,rgba(43,122,255,.55),rgba(124,76,255,.65));transform:perspective(70px) rotateX(12deg);box-shadow:0 10px 35px rgba(44,105,255,.45)}
.visual-platform{position:absolute;bottom:42px;width:400px;height:85px;border:1px solid rgba(48,172,255,.46);border-radius:50%;transform:perspective(200px) rotateX(62deg);background:radial-gradient(ellipse,rgba(36,118,255,.24),rgba(85,54,255,.06) 55%,transparent 72%);box-shadow:0 0 40px rgba(35,121,255,.17)}
.floating-chip{position:absolute;z-index:4;padding:12px 14px;border:1px solid rgba(113,168,255,.34);border-radius:13px;background:rgba(12,28,62,.88);box-shadow:0 12px 35px rgba(0,0,0,.20);backdrop-filter:blur(12px);color:#eaf3ff;font-size:11px;font-weight:750}
.floating-chip i{margin-right:7px;color:#54dfb1}.chip-approved{top:18%;left:0}.chip-score{top:15%;right:0;min-width:108px;text-align:center}.score-number{display:block;margin-top:2px;font-size:25px;color:#75c6ff}
.chip-eligible{right:0;bottom:18%;display:flex;align-items:center;gap:10px}.chip-avatar{display:grid;place-items:center;width:34px;height:34px;border-radius:10px;background:rgba(50,143,255,.25);color:#70d8ff;font-size:17px}
.chip-eligible small{display:block;color:#a5b9dd;font-weight:500}.chip-eligible strong{display:block;font-size:12px}
.mini-bars{display:flex;align-items:end;gap:5px;height:32px;margin-top:7px}.mini-bars span{width:8px;border-radius:3px 3px 0 0;background:linear-gradient(#54d8ff,#4775ff)}
.mini-bars span:nth-child(1){height:35%}.mini-bars span:nth-child(2){height:60%}.mini-bars span:nth-child(3){height:44%}.mini-bars span:nth-child(4){height:88%}.mini-bars span:nth-child(5){height:70%}

/* Features */
.section-heading{max-width:740px;margin:0 auto 38px;text-align:center}.section-heading .eyebrow{margin-bottom:14px}
.section-heading h2{font-size:clamp(30px,3.7vw,43px);line-height:1.2;letter-spacing:-1.7px;font-weight:850}
.section-heading p{margin-top:14px;color:var(--muted);font-size:14px;line-height:1.8}
.features-section{padding-top:35px}
.features-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:17px}
.feature-card{display:flex;gap:17px;padding:22px;border:1px solid var(--border);border-radius:18px;background:linear-gradient(135deg,rgba(24,46,91,.76),rgba(14,27,58,.78));backdrop-filter:blur(14px);transition:transform .25s,border-color .25s,background .25s}
.feature-card:hover{transform:translateY(-5px);border-color:rgba(86,162,255,.55);background:linear-gradient(135deg,rgba(30,59,116,.86),rgba(18,31,68,.86))}
.feature-icon{display:grid;place-items:center;width:58px;height:58px;flex:0 0 58px;border-radius:15px;background:linear-gradient(145deg,rgba(37,146,255,.28),rgba(106,77,255,.26));border:1px solid rgba(105,165,255,.2);color:#64c9ff;font-size:24px}
.feature-card h3{margin:1px 0 7px;font-size:16px;font-weight:800}.feature-card p{color:#b0c1df;font-size:12px;line-height:1.8}
.feature-link{display:inline-block;margin-top:10px;color:#77bfff;font-size:11px;font-weight:800}

/* EMI calculator */
.emi-wrap{display:grid;grid-template-columns:.8fr 1.2fr;gap:26px;align-items:stretch;padding:25px;border:1px solid rgba(91,144,255,.37);border-radius:24px;background:linear-gradient(115deg,rgba(13,35,77,.88),rgba(25,24,76,.83));box-shadow:0 22px 70px rgba(0,0,0,.16);overflow:hidden}
.emi-intro{align-self:center;padding:10px}.emi-intro .eyebrow{margin-bottom:15px;font-size:10px}
.emi-intro h2{margin-bottom:12px;font-size:clamp(27px,3vw,36px);line-height:1.2;letter-spacing:-1.2px}
.emi-intro p{margin-bottom:20px;color:#b7c9e8;font-size:13px;line-height:1.8}
.emi-panel{padding:22px;border:1px solid rgba(148,177,255,.20);border-radius:18px;background:rgba(6,16,41,.50)}
.emi-input-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px}
.form-field label{display:block;margin-bottom:8px;color:#dce8ff;font-size:12px;font-weight:700}
.form-field input{width:100%;min-width:0;height:43px;padding:0 12px;color:white;border:1px solid rgba(157,190,255,.17);border-radius:10px;outline:none;background:rgba(32,54,105,.72)}
.form-field input:focus{border-color:#579eff;box-shadow:0 0 0 3px rgba(57,135,255,.12)}
.emi-submit{width:100%;margin-top:14px}
.emi-results{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;margin-top:17px}
.emi-result-box{padding:12px;border:1px solid rgba(157,190,255,.12);border-radius:12px;background:rgba(255,255,255,.035)}
.emi-result-box span{display:block;color:#a9bbdc;font-size:10px;line-height:1.5}
.emi-result-box strong{display:block;margin-top:5px;color:#f3f7ff;font-size:15px;overflow-wrap:anywhere}
.emi-disclaimer{margin-top:12px;color:#8ea4ca;font-size:10px;line-height:1.7}

/* About */
.cta-card{padding:48px 30px;text-align:center;border:1px solid var(--border);border-radius:23px;background:radial-gradient(ellipse at 50% 0%,rgba(66,103,255,.18),transparent 60%),rgba(12,24,52,.77);backdrop-filter:blur(15px)}
.cta-card h2{max-width:720px;margin:0 auto 14px;font-size:clamp(29px,3.5vw,40px);line-height:1.2;letter-spacing:-1.2px}
.cta-card p{max-width:650px;margin:0 auto 23px;color:var(--muted);font-size:14px;line-height:1.8}

/* Footer */
.footer{position:relative;z-index:1;padding:25px 0;border-top:1px solid rgba(157,190,255,.13);background:rgba(5,11,27,.84);backdrop-filter:blur(16px)}
.footer-inner{display:flex;align-items:center;justify-content:space-between;gap:22px}
.footer-brand{display:flex;align-items:center;gap:10px;font-size:14px;font-weight:800;white-space:nowrap}
.footer-brand .brand-icon{width:31px;height:31px;border-radius:9px;font-size:13px}
.footer-copy,.footer-note{color:#9eb0d0;font-size:10px}.footer-links{display:flex;flex-wrap:wrap;gap:20px}
.footer-links a{color:#b8c7e2;font-size:11px}.footer-links a:hover{color:white}
.footer-note{max-width:300px;line-height:1.7}

/* Responsive */
@media(max-width:1050px){
.container{width:calc(100% - 44px)}.hero-grid{gap:25px;grid-template-columns:1fr .9fr}h1{font-size:clamp(42px,5vw,58px);letter-spacing:-2.4px}
.visual-stage{transform:scale(.9);margin:-15px}.nav-links{gap:17px}.features-grid{grid-template-columns:1fr}.emi-wrap{grid-template-columns:1fr}.emi-intro{padding:0}
}
@media(max-width:760px){
.container{width:calc(100% - 34px)}.nav-inner{min-height:72px;flex-wrap:wrap;gap:12px;padding:12px 0}
.brand{font-size:19px}.brand-icon{width:39px;height:39px}.menu-toggle{display:inline-grid;place-items:center;margin-left:auto}
.nav-links{display:none;order:4;width:100%;flex-direction:column;align-items:stretch;gap:0;padding:8px 0}.nav-links.open{display:flex}.nav-links a{padding:10px 4px}
.nav-actions{gap:8px}.nav-actions .btn{min-height:38px;padding:0 12px;font-size:11px}
.hero{padding:52px 0 35px}.hero-grid{grid-template-columns:1fr;gap:22px}h1{font-size:clamp(39px,10vw,58px);letter-spacing:-2.3px}
.hero-description{font-size:14px;margin:18px 0 22px}.hero-buttons .btn{min-height:48px;padding:0 14px;font-size:11px}.trust-row{gap:13px;font-size:10px}
.visual-stage{min-height:350px;transform:scale(.86);margin:-20px 0}.orbit{width:310px;height:310px}.visual-platform{width:340px}
.chip-approved{left:-3px}.chip-score{right:-3px}.chip-eligible{right:-3px;bottom:12%}
.section{padding:65px 0}.features-section{padding-top:28px}.section-heading{margin-bottom:26px}.section-heading h2{font-size:30px}
.feature-card{padding:17px;gap:13px}.feature-icon{width:48px;height:48px;flex-basis:48px;font-size:20px}
.emi-wrap{padding:17px;gap:20px}.emi-panel{padding:15px}.emi-input-grid{grid-template-columns:1fr;gap:12px}.emi-results{grid-template-columns:1fr}
.emi-result-box{display:flex;justify-content:space-between;align-items:center;gap:12px}.emi-result-box strong{margin-top:0;text-align:right}
.cta-card{padding:35px 20px}.footer-inner{align-items:flex-start;flex-direction:column}.footer-note{max-width:none}
}
@media(max-width:390px){
.container{width:calc(100% - 26px)}.nav-actions .btn{padding:0 9px}.hero-buttons{align-items:stretch;flex-direction:column}.hero-buttons .btn{width:100%}
.visual-stage{transform:scale(.75);margin:-45px -25px}.trust-row{flex-direction:column}
}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;scroll-behavior:auto!important;transition-duration:.01ms!important}}
</style>
</head>
<body>
<div class="tech-background" aria-hidden="true">
    <div class="tech-grid"></div><div class="data-points"></div>
    <div class="data-wave wave-one"></div><div class="data-wave wave-two"></div><div class="data-wave wave-three"></div>
    <div class="ambient-light light-blue"></div><div class="ambient-light light-purple"></div>
</div>

<header class="navbar">
    <div class="container nav-inner">
        <a class="brand" href="index.php" aria-label="FinIntel AI home"><span class="brand-icon"><i class="fa-solid fa-chart-line"></i></span><span>FinIntel <span>AI</span></span></a>
        <button class="menu-toggle" id="menuToggle" type="button" aria-label="Toggle navigation" aria-expanded="false"><i class="fa-solid fa-bars"></i></button>
        <nav class="nav-links" id="navLinks" aria-label="Main navigation">
            <a href="#home">Home</a><a href="#features">Features</a><a href="#emi">EMI Calculator</a><a href="#about">About</a>
        </nav>
        <div class="nav-actions"><a class="btn btn-outline" href="login.php">Login</a><a class="btn btn-primary" href="register.php">Get Started <i class="fa-solid fa-arrow-right"></i></a></div>
    </div>
</header>

<main>
<section class="hero" id="home">
    <div class="container hero-grid">
        <div class="hero-copy">
            <div class="eyebrow"><i class="fa-solid fa-wand-magic-sparkles"></i> Smart Financial Technology</div>
            <h1>Make Smarter <span class="gradient-text">Loan Decisions</span> with AI.</h1>
            <p class="hero-description">Understand your loan eligibility, explore financial insights, and manage your applications through one simple and intelligent platform.</p>
            <div class="hero-buttons">
                <a class="btn btn-primary" href="register.php">Create Free Account <i class="fa-solid fa-arrow-right"></i></a>
                <a class="btn btn-outline" href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Login to Dashboard</a>
            </div>
            <div class="trust-row"><span><i class="fa-solid fa-shield-halved"></i> Secure &amp; Private</span><span><i class="fa-solid fa-bolt"></i> Instant Analysis</span><span><i class="fa-regular fa-clock"></i> Track Applications</span></div>
        </div>
        <div class="visual-stage" aria-label="Illustration of AI-powered financial eligibility">
            <div class="orbit"></div><div class="visual-platform"></div>
            <div class="bank-illustration" aria-hidden="true">
                <div class="bank-glow"></div><div class="bank-roof"><i class="fa-solid fa-landmark"></i></div>
                <div class="bank-columns"><span></span><span></span><span></span><span></span></div><div class="bank-base"></div>
            </div>
            <div class="floating-chip chip-approved"><i class="fa-solid fa-circle-check"></i> Loan planning ready</div>
            <div class="floating-chip chip-score"><span>AI INSIGHTS</span><strong class="score-number">92%</strong><div class="mini-bars"><span></span><span></span><span></span><span></span><span></span></div></div>
            <div class="floating-chip chip-eligible"><span class="chip-avatar"><i class="fa-solid fa-user-check"></i></span><span><small>Your workspace</small><strong>Eligibility tools</strong></span></div>
        </div>
    </div>
</section>

<section class="section features-section" id="features">
    <div class="container">
        <div class="section-heading"><div class="eyebrow"><i class="fa-solid fa-layer-group"></i> Platform Features</div><h2>Everything you need, <span class="gradient-text">in one place.</span></h2><p>Explore helpful tools for loan planning, financial analysis, and keeping your application information organized.</p></div>
        <div class="features-grid">
            <article class="feature-card"><div class="feature-icon"><i class="fa-solid fa-brain"></i></div><div><h3>AI Loan Analysis</h3><p>Explore eligibility insights using the information and analysis tools available in your account.</p><a class="feature-link" href="register.php">Get started <i class="fa-solid fa-arrow-right"></i></a></div></article>
            <article class="feature-card"><div class="feature-icon"><i class="fa-solid fa-chart-column"></i></div><div><h3>Financial Analytics</h3><p>Review financial information with clear summaries and visual insights where supported.</p><a class="feature-link" href="login.php">Explore workspace <i class="fa-solid fa-arrow-right"></i></a></div></article>
            <article class="feature-card"><div class="feature-icon"><i class="fa-regular fa-clock"></i></div><div><h3>Application History</h3><p>Use your account workspace to review and manage saved application information.</p><a class="feature-link" href="login.php">Login to continue <i class="fa-solid fa-arrow-right"></i></a></div></article>
        </div>
    </div>
</section>

<section class="section" id="emi">
    <div class="container">
        <div class="emi-wrap">
            <div class="emi-intro"><div class="eyebrow"><i class="fa-solid fa-calculator"></i> Smart Loan Planning</div><h2>Calculate Your Loan <span class="gradient-text">EMI</span></h2><p>Estimate monthly payments, total interest, and total repayment before you make a borrowing decision.</p><a class="btn btn-outline" href="#emi-calculator">Open Calculator <i class="fa-solid fa-arrow-right"></i></a></div>
            <div class="emi-panel" id="emi-calculator">
                <div class="emi-input-grid">
                    <div class="form-field"><label for="loanAmount">Loan Amount (₹)</label><input type="number" id="loanAmount" min="1000" max="100000000" step="1000" value="500000" inputmode="numeric"></div>
                    <div class="form-field"><label for="interestRate">Interest Rate (% per year)</label><input type="number" id="interestRate" min="0" max="50" step="0.1" value="8.5" inputmode="decimal"></div>
                    <div class="form-field"><label for="loanTenure">Tenure (Years)</label><input type="number" id="loanTenure" min="1" max="40" step="1" value="5" inputmode="numeric"></div>
                </div>
                <button class="btn btn-primary emi-submit" id="calculateEmi" type="button"><i class="fa-solid fa-calculator"></i> Calculate EMI <i class="fa-solid fa-arrow-right"></i></button>
                <div class="emi-results" aria-live="polite">
                    <div class="emi-result-box"><span>Monthly EMI</span><strong id="monthlyEmi">₹10,259</strong></div>
                    <div class="emi-result-box"><span>Total Interest</span><strong id="totalInterest">₹1,15,540</strong></div>
                    <div class="emi-result-box"><span>Total Repayment</span><strong id="totalPayment">₹6,15,540</strong></div>
                </div>
                <p class="emi-disclaimer">Illustrative estimate only. Actual loan terms, fees, eligibility, and interest rates depend on the lender. This calculator does not guarantee loan approval.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" id="about">
    <div class="container"><div class="cta-card">
        <div class="eyebrow"><i class="fa-solid fa-wand-magic-sparkles"></i> Start with clarity</div>
        <h2>Make your next financial decision with <span class="gradient-text">more confidence.</span></h2>
        <p>Create an account to access the FinIntel AI workspace and explore the tools available in your project.</p>
        <a class="btn btn-primary" href="register.php">Get Started for Free <i class="fa-solid fa-arrow-right"></i></a>
    </div></div>
</section>
</main>

<footer class="footer">
    <div class="container footer-inner">
        <a class="footer-brand" href="index.php"><span class="brand-icon"><i class="fa-solid fa-chart-line"></i></span><span>FinIntel AI</span></a>
        <p class="footer-copy">&copy; <?php echo date('Y'); ?> FinIntel AI. All rights reserved.</p>
        <nav class="footer-links" aria-label="Footer navigation"><a href="#home">Home</a><a href="#features">Features</a><a href="login.php">Login</a><a href="register.php">Register</a></nav>
        <p class="footer-note"><i class="fa-solid fa-circle-info"></i> Financial estimates are informational only and do not guarantee loan approval.</p>
    </div>
</footer>

<script>
const menuToggle = document.getElementById('menuToggle');
const navLinks = document.getElementById('navLinks');

menuToggle.addEventListener('click', () => {
    const isOpen = navLinks.classList.toggle('open');
    menuToggle.setAttribute('aria-expanded', String(isOpen));
    menuToggle.innerHTML = isOpen ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
});
navLinks.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
    navLinks.classList.remove('open');
    menuToggle.setAttribute('aria-expanded', 'false');
    menuToggle.innerHTML = '<i class="fa-solid fa-bars"></i>';
}));

const amountInput = document.getElementById('loanAmount');
const rateInput = document.getElementById('interestRate');
const tenureInput = document.getElementById('loanTenure');
const inr = new Intl.NumberFormat('en-IN', {style:'currency', currency:'INR', maximumFractionDigits:0});

function calculateEmi() {
    const principal = Number(amountInput.value);
    const annualRate = Number(rateInput.value);
    const years = Number(tenureInput.value);

    if (!Number.isFinite(principal) || principal < 1000 || principal > 100000000) {
        alert('Enter a loan amount between ₹1,000 and ₹10 crore.');
        amountInput.focus(); return;
    }
    if (!Number.isFinite(annualRate) || annualRate < 0 || annualRate > 50) {
        alert('Enter an annual interest rate between 0% and 50%.');
        rateInput.focus(); return;
    }
    if (!Number.isFinite(years) || years < 1 || years > 40) {
        alert('Enter a tenure between 1 and 40 years.');
        tenureInput.focus(); return;
    }

    const months = years * 12;
    const monthlyRate = annualRate / 1200;
    const emi = monthlyRate === 0
        ? principal / months
        : principal * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
    const total = emi * months;
    document.getElementById('monthlyEmi').textContent = inr.format(emi);
    document.getElementById('totalInterest').textContent = inr.format(total - principal);
    document.getElementById('totalPayment').textContent = inr.format(total);
}
document.getElementById('calculateEmi').addEventListener('click', calculateEmi);
[amountInput, rateInput, tenureInput].forEach(input => input.addEventListener('keydown', event => {
    if (event.key === 'Enter') calculateEmi();
}));
calculateEmi();
</script>
</body>
</html>
