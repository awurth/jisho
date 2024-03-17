import {NextRequest, NextResponse} from "next/server";

export function middleware(request: NextRequest) {
  console.log(request);
  // return NextResponse.redirect(new URL('/home', request.url))
}
