import NextAuth from "next-auth";
import CredentialsProvider from "next-auth/providers/credentials";
import axios from "axios";

export default NextAuth({
    providers: [
        CredentialsProvider({
            name: "Credentials",
            credentials: {
                email: { label: "Email", type: "email" },
                password: { label: "Password", type: "password" }
            },
            async authorize(credentials) {
                try {
                    console.log("Attempting login with:", credentials);

                    const res = await axios.post('http://localhost:8000/api/login', {
                        email: credentials.email,
                        password: credentials.password
                    }, {
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });

                    console.log("Response status:", res.status);
                    console.log("Response data:", res.data);

                    const data = res.data;

                    if (data.token) {
                        return {
                            id: credentials.email,  // or any placeholder since you don't have user details here
                            name: credentials.email,
                            email: credentials.email,
                            token: data.token
                        };
                    }

                    return null;


                    console.error("No token found in response.");
                    return null;
                } catch (error) {
                    console.error("Login error:", error.response?.data || error.message);
                    return null;
                }
            }
        })
    ],
    callbacks: {
        async jwt({ token, user }) {
            if (user) {
                token.accessToken = user.token;
                token.id = user.id;
            }
            return token;
        },
        async session({ session, token }) {
            session.accessToken = token.accessToken;
            session.user.id = token.id;
            return session;
        }
    },
    session: {
        strategy: "jwt"
    },
    pages: {
        signIn: '/login'
    }
});
