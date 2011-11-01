#import "WebViewVC.h"
#import "rhead_sharepoint_ipAppDelegate.h"

@implementation WebViewVC

@synthesize webView, url, indicator;

- (void)viewDidLoad {
    [super viewDidLoad];
	NSDictionary* loginDict = [NSDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate loginDictPath]];
	NSString* loginPassStr = [NSString stringWithFormat:@"https://%@:%@@", [loginDict objectForKey:@"login"], [loginDict objectForKey:@"password"]];
	NSString* urlStr = [url stringByReplacingOccurrencesOfString:@"https://" withString:@""];
	if (!dontAppendPass) {
		urlStr = [NSString stringWithFormat:@"%@%@", loginPassStr, urlStr];
	}
	NSURLRequest* urlRequest = [NSURLRequest requestWithURL:[NSURL URLWithString:urlStr]];
	[webView loadRequest:urlRequest];
	NSLog(@"%@", urlStr);
	backBtn.hidden = YES;
	[self setUpTabBarButtons];
	webView.autoresizingMask = UIViewAutoresizingFlexibleHeight | UIViewAutoresizingFlexibleWidth;
}
- (BOOL)webView:(UIWebView *)webView shouldStartLoadWithRequest:(NSURLRequest *)request navigationType:(UIWebViewNavigationType)navigationType {
	self.indicator.hidden = NO;
	[self.indicator startAnimating];
	return YES;
}
//-(BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)orientation {
//    return YES;
//}
- (void)webViewDidFinishLoad:(UIWebView *)webView {
	self.indicator.hidden = YES;
	[self.indicator stopAnimating];
}
- (void)dealloc {
    [super dealloc];
	[webView release];
	[url release];
	[indicator release];
}

@end
