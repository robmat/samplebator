#import "WebViewVC.h"
#import "rhead_sharepoint_ipAppDelegate.h"

@implementation WebViewVC

@synthesize webView, url;

- (void)viewDidLoad {
    [super viewDidLoad];
	NSDictionary* loginDict = [NSDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate loginDictPath]];
	NSString* loginPassStr = [NSString stringWithFormat:@"https://%@:%@@", [loginDict objectForKey:@"login"], [loginDict objectForKey:@"password"]];
	NSString* urlStr = [url stringByReplacingOccurrencesOfString:@"https://" withString:@""];
	urlStr = [NSString stringWithFormat:@"%@%@", loginPassStr, urlStr];
	NSURLRequest* urlRequest = [NSURLRequest requestWithURL:[NSURL URLWithString:urlStr]];
	[webView loadRequest:urlRequest];
	NSLog(@"%@", urlStr);
}

- (void)dealloc {
    [super dealloc];
	[webView release];
	[url release];
}

@end
