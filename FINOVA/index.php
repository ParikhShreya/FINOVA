<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FINOVA | Smarter Money Decisions</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
<div class="nav-auth">
 <script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            /* ભાષાઓના કોડ: 
               gu: Gujarati, hi: Hindi, mr: Marathi, ta: Tamil, te: Telugu, 
               kn: Kannada, bn: Bengali, pa: Punjabi, ml: Malayalam,
               fr: French, de: German, es: Spanish
            */
            includedLanguages: 'en,gu,hi,mr,ta,te,kn,bn,pa,ml,fr,de,es', 
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    
    <header class="navbar">
        <div class="container flex-between">
            <div class="logo">
                <div ><img src="finovo_logo1.png"></div></img>
                <span>FINOVA</span>
            </div>
            <nav class="nav-actions">
                <button id="theme-toggle" class="btn-icon">
                    <i data-lucide="moon"></i>
                </button>
                <button class="btn-white"><a href = "signin.php"> Sign In</button></a>
                <button class="btn-white"><a href = "signup.php">Get Started</button></a>
            </nav>

            <!-- <div class="nav-right">
  <div class="language-btn">
    🌐 <span>Change Language</span>
    <div id="google_translate_element"></div>
  </div>
</div> -->

<div class="nav-right">
  <div id="theme-toggle"></div>
  <button class="language-btn">
    🌐 <span>Change Language</span>
    <div id="google_translate_element"></div>
  </button>
</div>


        </div>
    </header>

    <section class="hero">
        <div class="container text-center">
            <h1>
                <span class="gradient-text">Smarter Money Decisions,</span><br>
                Powered by AI.
            </h1>
            <p class="hero-subtitle">Goal-driven personal finance planning for students and young professionals.</p>
            <div class="hero-btns">
                <button class="btn-white lg"><a href = "Frontend/signin.html">Get Started <i data-lucide="arrow-right"></i> </a></button>
                <button class="btn-white lg"><a href="mobile.html"><i data-lucide="smartphone"></i> View Live App Demo</button>
                    </a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container text-center">
            <div class="badge"><i data-lucide="target"></i> How FINOVA Works</div>
            <h2>From Financial Data to Intelligent Insights</h2>
            
            

            <div class="flow-grid">
                <div class="card">
                    <i data-lucide="database" class="icon-blue"></i>
                    <h3>Financial Data</h3>
                    <p>Bank statements, expenses, investments</p>
                </div>
                <div class="arrow"><i data-lucide="arrow-right"></i></div>
                <div class="card">
                    <i data-lucide="brain" class="icon-indigo"></i>
                    <h3>AI Intelligence</h3>
                    <p>Multi-agent analysis & planning</p>
                </div>
                <div class="arrow"><i data-lucide="arrow-right"></i></div>
                <div class="card">
                    <i data-lucide="line-chart" class="icon-purple"></i>
                    <h3>Smart Insights</h3>
                    <p>Goals, alerts, recommendations</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-muted">
        <div class="container text-center">
            <h2>Complete Financial Planning Suite</h2>
            <div class="features-grid">
                <div class="feature-card bl-blue">
                    <i data-lucide="upload"></i>
                    <h3>Bank Statement Analysis</h3>
                    <p>Upload statements and get instant categorization of expenses with intelligent analysis.</p>
                </div>
                <div class="feature-card bl-green">
                    <i data-lucide="bar-chart-3"></i>
                    <h3>Investment Intelligence</h3>
                    <p>Overview with risk analysis and intelligent allocation recommendations.</p>
                </div>
                <div class="feature-card bl-purple">
                    <i data-lucide="target"></i>
                    <h3>Goal-First Planning</h3>
                    <p>Set and track goals for home, travel, or savings with AI-powered projections.</p>
                </div>
              <div class="feature-card bl-red">
    <i data-lucide="bell" class="h-12 w-12 text-red-600 mb-3"></i>
    <h3 class="text-xl font-semibold">Smart Alerts & Monitoring</h3>
    <p class="text-slate-600">Real-time risk monitoring, unusual spending alerts, and proactive notifications.</p>
</div>
                <div class="feature-card bl-blue">
                    <i data-lucide="target"></i>
                    <h3>Spending Insights</h3>
                    <p>Track spending trends, identify patterns, and optimize habits with AI-driven analysis and personalized tips</p>
                </div>
                <div class="feature-card bl-red" >
                    <i data-lucide="target"></i>
                    <h3>AI Financial Coach</h3>
                    <p>Get personalized advice and recommendations through an interactive AI assistant that understands your financial goals</p>
            </div>
        </div>
    </section>


<section class="ai-section">
  <div class="container">

    <!-- Header -->
    <div class="section-header">
      <span class="badge">
        🌐 Multi-Agent AI System
      </span>

      <h2>Intelligent Agent Collaboration</h2>

      <p>
        Our AI agents work together using MCP-style shared context to provide
        comprehensive, connected financial guidance that adapts to your unique situation
      </p>
    </div>

    <!-- Agents Grid -->
    <div class="agent-grid">

      <div class="agent-card blue">
        <div class="icon">📊</div>
        <h3>Spending Analysis Agent</h3>
        <p>Tracks expenses, identifies patterns, and categorizes spending behavior</p>
      </div>

      <div class="agent-card green">
        <div class="icon">📈</div>
        <h3>Investment Intelligence Agent</h3>
        <p>Analyzes portfolio, assesses risk, and optimizes allocation strategies</p>
      </div>

      <div class="agent-card purple">
        <div class="icon">🎯</div>
        <h3>Goal Planning Agent</h3>
        <p>Creates roadmaps, tracks progress, and adjusts timelines dynamically</p>
      </div>

      <div class="agent-card orange">
        <div class="icon">🔔</div>
        <h3>Risk & Alert Agent</h3>
        <p>Monitors risks, detects anomalies, and sends proactive alerts</p>
      </div>

      <div class="agent-card pink">
        <div class="icon">💡</div>
        <h3>Financial Coach Agent</h3>
        <p>Provides personalized advice and actionable recommendations</p>
      </div>

    </div>

    <!-- Shared Context -->
    <div class="shared-context">
      <h3>🌐 Shared Intelligence Context</h3>
      <p>
        All agents share context through an MCP-style architecture, ensuring recommendations
        are holistic, consistent, and aligned with your complete financial picture
      </p>
    </div>

  </div>
</section>

    

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo white">FINOVA</div>
                    <p>Empowering financial decisions through intelligent technology.</p>
                </div>
                <div>
                    <h4>Product</h4>
                    <a href="#">Features</a><a href="#">Security</a>
                </div>
                <div>
                    <h4>Support</h4>
                    <a href="#">Help Center</a><a href="#">Privacy Policy</a>
                </div>
            </div>
            <div class="footer-bottom">
                © 2024 FINOVA. All rights reserved.
            </div>
        </div>
    </footer>
    <script src="app.js"></script>

   <script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            // આ લિસ્ટમાં તમારી બધી જરુરી ભાષાઓ છે
            includedLanguages: 'en,gu,hi,mr,ta,te,kn,bn', 
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    </body>

</html>