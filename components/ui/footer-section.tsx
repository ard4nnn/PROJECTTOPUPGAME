'use client';
import React from 'react';
import type { ComponentProps, ReactNode } from 'react';
import { motion, useReducedMotion } from 'motion/react';
import { FacebookIcon, InstagramIcon, YoutubeIcon, MessageCircleIcon } from 'lucide-react';

interface FooterLink {
	title: string;
	href: string;
	icon?: React.ComponentType<{ className?: string }>;
}

interface FooterSection {
	label: string;
	links: FooterLink[];
}

const footerLinks: FooterSection[] = [
	{
		label: 'Layanan',
		links: [
			{ title: 'Top Up Game', href: '/' },
			{ title: 'Cek Transaksi', href: '/cek-transaksi' },
			{ title: 'Leaderboard', href: '/leaderboard' },
			{ title: 'Kalkulator Harga', href: '/kalkulator' },
		],
	},
	{
		label: 'Perusahaan',
		links: [
			{ title: 'Tentang Kami', href: '/about' },
			{ title: 'Kebijakan Privasi', href: '/privacy' },
			{ title: 'Syarat & Ketentuan', href: '/terms' },
			{ title: 'Bantuan & FAQ', href: '/faqs' },
		],
	},
	{
		label: 'Ikuti Kami',
		links: [
			{ title: 'Facebook', href: '#', icon: FacebookIcon },
			{ title: 'Instagram', href: '#', icon: InstagramIcon },
			{ title: 'Youtube', href: '#', icon: YoutubeIcon },
			{ title: 'WhatsApp', href: '#', icon: MessageCircleIcon },
		],
	},
];

export function Footer() {
	return (
		<footer className="md:rounded-t-6xl relative w-full max-w-6xl mx-auto flex flex-col items-center justify-center rounded-t-4xl border-t bg-[radial-gradient(35%_128px_at_50%_0%,theme(backgroundColor.white/8%),transparent)] px-6 py-12 lg:py-16">
			<div className="bg-foreground/20 absolute top-0 right-1/2 left-1/2 h-px w-1/3 -translate-x-1/2 -translate-y-1/2 rounded-full blur" />

			<div className="grid w-full gap-8 xl:grid-cols-3 xl:gap-8">
				<AnimatedContainer className="space-y-4">
					{/* Authentic FUNtopup Logo */}
					<div className="flex items-center gap-2 select-none">
						<svg viewBox="0 0 24 30" className="w-[22px] h-[28px] fill-[#fcd535] text-[#fcd535]">
							<path d="M14 2 L2 15 L10 15 L7 28 L20 13 L12 13 Z" />
						</svg>
						<span className="text-[26px] font-black italic tracking-tighter text-[#fcd535] uppercase flex items-center leading-none">
							FUN
							<span className="not-italic font-medium text-white normal-case ml-0.5">
								topup
							</span>
						</span>
					</div>
					
					<p className="text-muted-foreground mt-2 text-sm">
						FUNtopup menyediakan pengisian saldo voucher game paling murah, instan, dan aman 24 jam non-stop.
					</p>
					<p className="text-muted-foreground mt-8 text-sm md:mt-0">
						© {new Date().getFullYear()} FUNtopup. Semua Hak Cipta Dilindungi.
					</p>
				</AnimatedContainer>

				<div className="mt-10 grid grid-cols-2 gap-8 md:grid-cols-3 xl:col-span-2 xl:mt-0">
					{footerLinks.map((section, index) => (
						<AnimatedContainer key={section.label} delay={0.1 + index * 0.1}>
							<div className="mb-10 md:mb-0">
								<h3 className="text-xs font-semibold uppercase tracking-wider text-muted-foreground">{section.label}</h3>
								<ul className="text-muted-foreground mt-4 space-y-2 text-sm">
									{section.links.map((link) => (
										<li key={link.title}>
											<a
												href={link.href}
												className="hover:text-foreground inline-flex items-center transition-all duration-300"
											>
												{link.icon && <link.icon className="me-1.5 size-4" />}
												{link.title}
											</a>
										</li>
									))}
								</ul>
							</div>
						</AnimatedContainer>
					))}
				</div>
			</div>
		</footer>
	);
}

type ViewAnimationProps = {
	delay?: number;
	className?: ComponentProps<typeof motion.div>['className'];
	children: ReactNode;
};

function AnimatedContainer({ className, delay = 0.1, children }: ViewAnimationProps) {
	const shouldReduceMotion = useReducedMotion();

	if (shouldReduceMotion) {
		return children;
	}

	return (
		<motion.div
			initial={{ filter: 'blur(4px)', translateY: -8, opacity: 0 }}
			whileInView={{ filter: 'blur(0px)', translateY: 0, opacity: 1 }}
			viewport={{ once: true }}
			transition={{ delay, duration: 0.8 }}
			className={className}
		>
			{children}
		</motion.div>
	);
}
