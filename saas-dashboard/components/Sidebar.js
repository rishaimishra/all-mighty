import Link from 'next/link';
import { useRouter } from 'next/router';

export default function Sidebar() {
  const router = useRouter();

  const links = [
    { name: 'Dashboard', href: '/dashboard' },
    { name: 'Campaigns', href: '/campaigns' },
    { name: 'Ad Accounts', href: '/ad-accounts' },
    { name: 'Settings', href: '/settings' },
  ];

  return (
    <div className="w-64 bg-white border-r h-screen sticky top-0">
      <div className="p-6 text-xl font-bold border-b">SaaS Dashboard</div>
      <nav className="mt-6">
        {links.map((link) => (
          <Link key={link.name} href={link.href}>
            <a
              className={`block px-6 py-3 hover:bg-gray-100 ${
                router.pathname === link.href ? 'bg-gray-200 font-semibold' : ''
              }`}
            >
              {link.name}
            </a>
          </Link>
        ))}
      </nav>
    </div>
  );
}
