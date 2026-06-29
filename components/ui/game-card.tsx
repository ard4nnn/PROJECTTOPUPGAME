import * as React from "react";
import { cn } from "@/lib/utils";
import { ArrowRight } from "lucide-react";

interface GameCardProps extends React.HTMLAttributes<HTMLDivElement> {
  imageUrl: string;
  gameName: string;
  href: string;
  themeColor: string;
}

const GameCard = React.forwardRef<HTMLDivElement, GameCardProps>(
  ({ className, imageUrl, gameName, href, themeColor, ...props }, ref) => {
    return (
      <div
        ref={ref}
        style={{ "--theme-color": themeColor } as React.CSSProperties}
        className={cn("group w-full h-full", className)}
        {...props}
      >
        <a
          href={href}
          className="relative block w-full h-full rounded-2xl overflow-hidden transition-all duration-500 ease-in-out group-hover:scale-105"
          style={{ boxShadow: `0 0 40px -10px hsl(var(--theme-color) / 0.5)` }}
          aria-label={`Top up ${gameName}`}
        >
          {/* Background Image */}
          <div
            className="absolute inset-0 bg-cover bg-center transition-transform duration-500 ease-in-out group-hover:scale-110"
            style={{ backgroundImage: `url(${imageUrl})` }}
          />

          {/* Gradient Overlay */}
          <div
            className="absolute inset-0"
            style={{
              background: `linear-gradient(to top, hsl(var(--theme-color) / 0.95) 0%, hsl(var(--theme-color) / 0.5) 40%, transparent 70%)`,
            }}
          />

          {/* Content */}
          <div className="relative flex flex-col justify-end h-full p-5 gap-3">
            <p className="text-white text-lg font-bold">{gameName}</p>
            <div className="flex items-center justify-center gap-2 bg-yellow-400 hover:bg-yellow-300 transition-colors rounded-lg px-4 py-3">
              <span className="text-sm font-bold text-gray-900">Top Up Sekarang</span>
              <ArrowRight className="h-4 w-4 text-gray-900 transition-transform duration-300 group-hover:translate-x-1" />
            </div>
          </div>
        </a>
      </div>
    );
  }
);
GameCard.displayName = "GameCard";

export { GameCard };
