"use client";

import * as React from "react";
import { useState, useRef, useEffect } from "react";
import {
  Card,
  CardHeader,
  CardDescription,
  CardContent,
  CardFooter,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { Separator } from "@/components/ui/separator";
import {
  Tabs, TabsList, TabsTrigger, TabsContent,
} from "@/components/ui/tabs";
import {
  Eye, EyeOff, Chrome, Mail, Lock, User, Phone,
} from "lucide-react";

export default function FUNtopupAuthSection() {
  const [showLoginPw, setShowLoginPw] = useState(false);
  const [showSignupPw, setShowSignupPw] = useState(false);

  const canvasRef = useRef<HTMLCanvasElement | null>(null);
  useEffect(() => {
    const canvas = canvasRef.current;
    const ctx = canvas?.getContext("2d");
    if (!canvas || !ctx) return;

    const setSize = () => {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    };
    setSize();

    type P = { x: number; y: number; v: number; o: number };
    let ps: P[] = [];
    let raf = 0;

    const make = (): P => ({
      x: Math.random() * canvas.width,
      y: Math.random() * canvas.height,
      v: Math.random() * 0.25 + 0.05,
      o: Math.random() * 0.3 + 0.08,
    });

    const init = () => {
      ps = [];
      const count = Math.floor((canvas.width * canvas.height) / 9000);
      for (let i = 0; i < count; i++) ps.push(make());
    };

    const draw = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ps.forEach((p) => {
        p.y -= p.v;
        if (p.y < 0) {
          p.x = Math.random() * canvas.width;
          p.y = canvas.height + Math.random() * 40;
          p.v = Math.random() * 0.25 + 0.05;
          p.o = Math.random() * 0.3 + 0.08;
        }
        // Golden/yellow particles matching FUNtopup brand color (#FBBF24)
        ctx.fillStyle = `rgba(251,191,36,${p.o})`;
        ctx.fillRect(p.x, p.y, 0.8, 2.5);
      });
      raf = requestAnimationFrame(draw);
    };

    const onResize = () => { setSize(); init(); };
    window.addEventListener("resize", onResize);
    init();
    raf = requestAnimationFrame(draw);
    return () => {
      window.removeEventListener("resize", onResize);
      cancelAnimationFrame(raf);
    };
  }, []);

  return (
    <section className="relative w-full min-h-[calc(100vh-70px)] bg-zinc-950 text-zinc-50 flex items-center justify-center px-4 py-12 overflow-hidden">
      <style>{`
        /* Accent grid lines */
        .accent-lines{position:absolute;inset:0;pointer-events:none;opacity:.45}
        .hline,.vline{position:absolute;background:#27272a}
        .hline{left:0;right:0;height:1px;transform:scaleX(0);transform-origin:50% 50%;animation:drawX .7s ease forwards}
        .vline{top:0;bottom:0;width:1px;transform:scaleY(0);transform-origin:50% 0%;animation:drawY .8s ease forwards}
        .hline:nth-child(1){top:20%;animation-delay:.1s}
        .hline:nth-child(2){top:50%;animation-delay:.2s}
        .hline:nth-child(3){top:80%;animation-delay:.3s}
        .vline:nth-child(4){left:20%;animation-delay:.25s}
        .vline:nth-child(5){left:50%;animation-delay:.35s}
        .vline:nth-child(6){left:80%;animation-delay:.45s}
        @keyframes drawX{to{transform:scaleX(1)}}
        @keyframes drawY{to{transform:scaleY(1)}}

        /* Card fade-up */
        .card-animate{opacity:0;transform:translateY(16px);animation:fadeUp .7s cubic-bezier(.22,.61,.36,1) .2s forwards}
        @keyframes fadeUp{to{opacity:1;transform:translateY(0)}}

        /* Tab blur switch */
        .tab-shell{position:relative;min-height:500px}
        .tab-panel{transition:opacity .22s ease,filter .22s ease}
        .tab-panel[data-state="inactive"]{position:absolute;inset:0;opacity:0;filter:blur(8px);pointer-events:none}
        .tab-panel[data-state="active"]{position:relative;opacity:1;filter:blur(0px)}

        /* Auth tabs — active tab yellow accent */
        .auth-tabs [role="tablist"]{background:#0f0f10;border:1px solid #27272a;border-radius:10px;padding:4px}
        .auth-tabs [role="tab"]{font-size:13px;letter-spacing:.02em;color:#a1a1aa;transition:color .2s}
        .auth-tabs [role="tab"][data-state="active"]{background:#1c1c1e;border-radius:8px;box-shadow:inset 0 0 0 1px #FBBF24;color:#FBBF24;font-weight:700}

        /* Yellow center glow behind card */
        .yellow-glow{position:absolute;inset:0;pointer-events:none;background:radial-gradient(ellipse 55% 35% at 50% 50%, rgba(251,191,36,0.05), transparent 70%)}
      `}</style>

      {/* Golden particles */}
      <canvas
        ref={canvasRef}
        className="absolute inset-0 w-full h-full opacity-50 pointer-events-none"
      />

      {/* Subtle yellow center glow */}
      <div className="yellow-glow" />

      {/* Accent grid lines */}
      <div className="accent-lines">
        <div className="hline" /><div className="hline" /><div className="hline" />
        <div className="vline" /><div className="vline" /><div className="vline" />
      </div>

      {/* Auth Card */}
      <Card className="card-animate relative z-10 w-full max-w-md border border-yellow-400/20 bg-zinc-900/85 backdrop-blur-md shadow-[0_0_50px_rgba(251,191,36,0.10)]">
        <CardHeader className="space-y-1 text-center pb-2">
          {/* Authentic FUNtopup SVG Logo */}
          <div className="flex items-center justify-center mb-2 gap-2 select-none">
            <svg viewBox="0 0 24 30" className="w-[20px] h-[26px] fill-yellow-400 text-yellow-400">
              <path d="M14 2 L2 15 L10 15 L7 28 L20 13 L12 13 Z" />
            </svg>
            <span className="text-2xl font-black italic tracking-tighter text-yellow-400 uppercase flex items-center leading-none">
              FUN
              <span className="not-italic font-medium text-white normal-case ml-0.5">
                topup
              </span>
            </span>
          </div>
          <CardDescription className="text-zinc-400 text-xs">
            Platform top up game murah, cepat &amp; aman
          </CardDescription>
        </CardHeader>

        <CardContent>
          <Tabs defaultValue="masuk" className="auth-tabs w-full">
            <TabsList className="grid w-full grid-cols-2">
              <TabsTrigger value="masuk">Masuk</TabsTrigger>
              <TabsTrigger value="daftar">Daftar</TabsTrigger>
            </TabsList>

            <div className="tab-shell mt-6">

              {/* ===== TAB MASUK (LOGIN) ===== */}
              <TabsContent value="masuk" forceMount className="tab-panel space-y-5">
                <form action="" method="POST" className="space-y-5">
                  <div className="text-center mb-1">
                    <h2 className="text-xl font-bold text-zinc-50">Selamat Datang</h2>
                    <p className="text-sm text-zinc-400 mt-1">Silakan login ke akun FUNtopup Anda</p>
                  </div>

                  {/* Username / Email */}
                  <div className="grid gap-2">
                    <Label htmlFor="login-user" className="text-zinc-300">Username / Email</Label>
                    <div className="relative">
                      <User className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-500" />
                      <Input
                        id="login-user"
                        type="text"
                        name="username"
                        required
                        placeholder="Masukkan username Anda"
                        className="pl-10 bg-zinc-950 border-zinc-800 text-zinc-50 placeholder:text-zinc-600 focus-visible:ring-yellow-400/30 focus-visible:border-yellow-400/50"
                      />
                    </div>
                  </div>

                  {/* Password */}
                  <div className="grid gap-2">
                    <Label htmlFor="login-password" className="text-zinc-300">Password</Label>
                    <div className="relative">
                      <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-500" />
                      <Input
                        id="login-password"
                        type={showLoginPw ? "text" : "password"}
                        name="password"
                        required
                        placeholder="Masukkan password"
                        className="pl-10 pr-10 bg-zinc-950 border-zinc-800 text-zinc-50 placeholder:text-zinc-600 focus-visible:ring-yellow-400/30 focus-visible:border-yellow-400/50"
                      />
                      <button
                        type="button"
                        className="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-md text-zinc-400 hover:text-yellow-400 transition-colors"
                        onClick={() => setShowLoginPw((v) => !v)}
                        aria-label={showLoginPw ? "Sembunyikan password" : "Tampilkan password"}
                      >
                        {showLoginPw ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                      </button>
                    </div>
                  </div>

                  {/* Ingat saya + Lupa password */}
                  <div className="flex items-center justify-between">
                    <div className="flex items-center gap-2">
                      <Checkbox
                        id="ingat"
                        name="remember"
                        className="border-zinc-700 data-[state=checked]:bg-yellow-400 data-[state=checked]:border-yellow-400 data-[state=checked]:text-zinc-900"
                      />
                      <Label htmlFor="ingat" className="text-zinc-400 text-sm cursor-pointer">Ingat saya</Label>
                    </div>
                    <a href="#" className="text-sm text-yellow-400 hover:text-yellow-300 transition-colors">
                      Lupa password?
                    </a>
                  </div>

                  {/* Masuk Button */}
                  <Button type="submit" className="w-full h-11 rounded-lg bg-yellow-400 text-zinc-900 font-bold text-base hover:bg-yellow-300 transition-colors">
                    Masuk
                  </Button>
                </form>

                <div className="relative py-1">
                  <Separator className="bg-zinc-800" />
                  <span className="absolute left-1/2 -translate-x-1/2 -top-3 bg-zinc-900 px-2 text-[11px] uppercase tracking-widest text-zinc-500">
                    atau
                  </span>
                </div>

                {/* Google only — GitHub removed */}
                <Button
                  variant="outline"
                  className="w-full h-10 rounded-lg border-zinc-700 bg-zinc-950 text-zinc-50 hover:bg-zinc-800 hover:border-yellow-400/40 transition-colors"
                >
                  <img src="/assets/images/google.png" alt="Google" className="h-4 w-4 mr-2 object-contain inline-block" />
                  Masuk dengan Google
                </Button>
              </TabsContent>

              {/* ===== TAB DAFTAR (REGISTER) ===== */}
              <TabsContent value="daftar" forceMount className="tab-panel space-y-4">
                <form action="" method="POST" className="space-y-4">
                  <div className="text-center mb-1">
                    <h2 className="text-xl font-bold text-zinc-50">Daftar Akun</h2>
                    <p className="text-sm text-zinc-400 mt-1">Buat akun FUNtopup Anda sekarang</p>
                  </div>

                  {/* Username */}
                  <div className="grid gap-2">
                    <Label htmlFor="reg-username" className="text-zinc-300">Username</Label>
                    <div className="relative">
                      <User className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-500" />
                      <Input
                        id="reg-username"
                        type="text"
                        name="username"
                        required
                        placeholder="Pilih username unik"
                        className="pl-10 bg-zinc-950 border-zinc-800 text-zinc-50 placeholder:text-zinc-600 focus-visible:ring-yellow-400/30 focus-visible:border-yellow-400/50"
                      />
                    </div>
                  </div>

                  {/* Email */}
                  <div className="grid gap-2">
                    <Label htmlFor="reg-email" className="text-zinc-300">Email</Label>
                    <div className="relative">
                      <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-500" />
                      <Input
                        id="reg-email"
                        type="email"
                        name="email"
                        required
                        placeholder="Masukkan alamat email aktif"
                        className="pl-10 bg-zinc-950 border-zinc-800 text-zinc-50 placeholder:text-zinc-600 focus-visible:ring-yellow-400/30 focus-visible:border-yellow-400/50"
                      />
                    </div>
                  </div>

                  {/* No. HP / WhatsApp */}
                  <div className="grid gap-2">
                    <Label htmlFor="reg-phone" className="text-zinc-300">No. HP / WhatsApp</Label>
                    <div className="relative">
                      <Phone className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-500" />
                      <Input
                        id="reg-phone"
                        type="tel"
                        name="phone"
                        required
                        placeholder="Contoh: 0812345678"
                        className="pl-10 bg-zinc-950 border-zinc-800 text-zinc-50 placeholder:text-zinc-600 focus-visible:ring-yellow-400/30 focus-visible:border-yellow-400/50"
                      />
                    </div>
                  </div>

                  {/* Password */}
                  <div className="grid gap-2">
                    <Label htmlFor="reg-password" className="text-zinc-300">Password</Label>
                    <div className="relative">
                      <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-500" />
                      <Input
                        id="reg-password"
                        type={showSignupPw ? "text" : "password"}
                        name="password"
                        required
                        placeholder="Gunakan minimal 6 karakter"
                        className="pl-10 pr-10 bg-zinc-950 border-zinc-800 text-zinc-50 placeholder:text-zinc-600 focus-visible:ring-yellow-400/30 focus-visible:border-yellow-400/50"
                      />
                      <button
                        type="button"
                        className="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-md text-zinc-400 hover:text-yellow-400 transition-colors"
                        onClick={() => setShowSignupPw((v) => !v)}
                        aria-label={showSignupPw ? "Sembunyikan password" : "Tampilkan password"}
                      >
                        {showSignupPw ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                      </button>
                    </div>
                  </div>

                  {/* Setuju Syarat & Ketentuan */}
                  <div className="flex items-start gap-2">
                    <Checkbox
                      id="terms"
                      required
                      className="mt-0.5 border-zinc-700 data-[state=checked]:bg-yellow-400 data-[state=checked]:border-yellow-400 data-[state=checked]:text-zinc-900"
                    />
                    <Label htmlFor="terms" className="text-zinc-400 text-sm cursor-pointer leading-snug">
                      Saya setuju dengan{" "}
                      <a href="/terms" className="text-yellow-400 hover:text-yellow-300 transition-colors">
                        Syarat &amp; Ketentuan
                      </a>{" "}
                      FUNtopup
                    </Label>
                  </div>

                  {/* Daftar Button */}
                  <Button type="submit" className="w-full h-11 rounded-lg bg-yellow-400 text-zinc-900 font-bold text-base hover:bg-yellow-300 transition-colors">
                    Daftar Akun
                  </Button>
                </form>

                <div className="relative py-1">
                  <Separator className="bg-zinc-800" />
                  <span className="absolute left-1/2 -translate-x-1/2 -top-3 bg-zinc-900 px-2 text-[11px] uppercase tracking-widest text-zinc-500">
                    atau
                  </span>
                </div>

                {/* Google only — GitHub removed */}
                <Button
                  variant="outline"
                  className="w-full h-10 rounded-lg border-zinc-700 bg-zinc-950 text-zinc-50 hover:bg-zinc-800 hover:border-yellow-400/40 transition-colors"
                >
                  <img src="/assets/images/google.png" alt="Google" className="h-4 w-4 mr-2 object-contain inline-block" />
                  Daftar dengan Google
                </Button>
              </TabsContent>

            </div>
          </Tabs>
        </CardContent>

        <CardFooter className="flex items-center justify-center text-sm text-zinc-400 pt-0">
          Butuh bantuan?{" "}
          <a className="ml-1 text-yellow-400 hover:text-yellow-300 hover:underline transition-colors" href="/faqs">
            Lihat FAQ
          </a>
        </CardFooter>
      </Card>
    </section>
  );
}
