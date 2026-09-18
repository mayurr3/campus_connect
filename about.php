<?php
// ==========================================================
// Campus Connect — About Project Page
// File: about.php
// Purpose: Explains system features, architecture, and BCA objectives
// ==========================================================

$page_title = "About Project";
require_once "config/database.php";
require_once "includes/header.php";
?>

<section class="section">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header">
            <span class="section-subtitle">Project Overview</span>
            <h1 class="section-title">About Campus Connect</h1>
            <p class="section-desc">
                A modern web-based college event management system engineered for seamless student participation and
                centralized campus administration.
            </p>
        </div>

        <!-- Main Narrative Grid -->
        <div class="glass-card" style="padding: 48px 40px; margin-bottom: 48px;">
            <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 40px; align-items: center;">
                <div>
                    <h2 style="font-size: 2rem; margin-bottom: 16px;">
                        Simplifying College Events with Technology
                    </h2>
                    <p style="color: var(--text-muted); margin-bottom: 16px; font-size: 1.05rem;">
                        <strong>Campus Connect</strong> is designed to solve the common issues in traditional college
                        event coordination — such as paper registration forms, scattered WhatsApp flyers, duplicate
                        registrations, and unorganized attendance records.
                    </p>
                    <p style="color: var(--text-muted); font-size: 1.05rem;">
                        By integrating student authentication, real-time event discovery, and administrative management
                        into a single responsive web portal, Campus Connect makes event organization efficient,
                        paperless, and transparent.
                    </p>
                </div>
                <div
                    style="background: rgba(121, 40, 202, 0.1); border: 1px solid rgba(121, 40, 202, 0.3); border-radius: var(--radius-md); padding: 30px;">
                    <h3
                        style="color: var(--primary-pink); font-size: 1.3rem; margin-bottom: 14px; display: flex; align-items: center; gap: 10px;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2" />
                            <circle cx="12" cy="12" r="6" stroke-width="2" />
                            <circle cx="12" cy="12" r="2" stroke-width="2" />
                        </svg>
                        Project Highlights
                    </h3>
                    <ul
                        style="list-style: none; display: flex; flex-direction: column; gap: 12px; color: var(--text-main);">
                        <li style="display: flex; align-items: center; gap: 10px;"><span class="pill-dot"></span>
                            <strong>Academic Evaluation:</strong> BCA 25 Marks Project
                        </li>
                        <li style="display: flex; align-items: center; gap: 10px;"><span class="pill-dot"></span>
                            <strong>Security:</strong> Bcrypt Password Hashing & Prepared Statements
                        </li>
                        <li style="display: flex; align-items: center; gap: 10px;"><span class="pill-dot"></span>
                            <strong>Performance:</strong> Fast Lightweight PHP + MySQL
                        </li>
                        <li style="display: flex; align-items: center; gap: 10px;"><span class="pill-dot"></span>
                            <strong>Design:</strong> Responsive Dark Mode Figma UI
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Why Campus Connect Features Grid -->
        <div class="section-header" style="margin-bottom: 36px;">
            <span class="section-subtitle">Core Capabilities</span>
            <h2 class="section-title">Why Campus Connect?</h2>
        </div>

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-bottom: 60px;">
            <div class="glass-card" style="padding: 30px;">
                <div
                    style="width: 48px; height: 48px; border-radius: 12px; background: rgba(121, 40, 202, 0.15); color: #c084fc; display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 10px;">Easy Event Discovery</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    Students can search and filter through upcoming technical, cultural, sports, and workshop events by
                    title or category with zero hassle.
                </p>
            </div>

            <div class="glass-card" style="padding: 30px;">
                <div
                    style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255, 0, 128, 0.15); color: #f472b6; display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 10px;">Simple Event Registration</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    One-click registration for logged-in students. Smart MySQL validation prevents duplicate
                    registrations automatically.
                </p>
            </div>

            <div class="glass-card" style="padding: 30px;">
                <div
                    style="width: 48px; height: 48px; border-radius: 12px; background: rgba(6, 182, 212, 0.15); color: #67e8f9; display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 10px;">Centralized Event Management</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    College administrators have access to full CRUD capabilities to publish, update, and manage event
                    schedules and venues.
                </p>
            </div>

            <div class="glass-card" style="padding: 30px;">
                <div
                    style="width: 48px; height: 48px; border-radius: 12px; background: rgba(250, 204, 21, 0.15); color: #fde047; display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 10px;">Student-Friendly Interface</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    Clean dashboard allowing students to monitor all their registered events, event dates, and personal
                    profile information.
                </p>
            </div>

            <div class="glass-card" style="padding: 30px;">
                <div
                    style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.15); color: #6ee7b7; display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 10px;">Organized Registration Records</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    Relational database mapping connects student accounts directly to registrations for instant
                    attendance tracking.
                </p>
            </div>

            <div class="glass-card" style="padding: 30px;">
                <div
                    style="width: 48px; height: 48px; border-radius: 12px; background: rgba(168, 85, 247, 0.15); color: #d8b4fe; display: flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 10px;">Offline Local Execution</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    Operates reliably on local WAMPServer with standard Apache and MySQL, ideal for college
                    demonstrations and viva evaluation.
                </p>
            </div>
        </div>

        <!-- Technology Stack for Viva Defense -->
        <div class="glass-card" style="padding: 40px; border-left: 4px solid var(--primary-purple);">
            <h3 style="font-size: 1.6rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                Technologies Used (BCA Viva Reference)
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div>
                    <h4 style="color: var(--accent-cyan); margin-bottom: 6px;">HTML5</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Provides semantic page architecture,
                        accessible forms, and card layouts.</p>
                </div>
                <div>
                    <h4 style="color: var(--primary-pink); margin-bottom: 6px;">CSS3</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Implements glassmorphism, responsive grid
                        layouts, and Figma dark neon styling.</p>
                </div>
                <div>
                    <h4 style="color: var(--accent-yellow); margin-bottom: 6px;">JavaScript</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Handles dynamic search, category filtering,
                        password toggles, and delete confirmations.</p>
                </div>
                <div>
                    <h4 style="color: #c084fc; margin-bottom: 6px;">PHP</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Handles server-side logic, session security,
                        password hashing, and MySQL communication.</p>
                </div>
                <div>
                    <h4 style="color: var(--accent-green); margin-bottom: 6px;">MySQL</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Stores student profiles, administrator
                        credentials, events, and registration records.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once "includes/footer.php"; ?>