"use client";
import FUNtopupAuthSection from "@/components/ui/login-signup";

export default function AuthPage() {
  return (
    <div className="relative flex flex-col min-h-screen bg-zinc-950">
      {/* 
        IMPORTANT: Your existing PHP header/navbar goes above this component.
        This component only renders the section below the nav.
        Do NOT wrap this in a fixed/full-screen container.
      */}
      <FUNtopupAuthSection />
    </div>
  );
}
