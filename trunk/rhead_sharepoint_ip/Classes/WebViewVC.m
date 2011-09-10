#import "WebViewVC.h"

@implementation WebViewVC

@synthesize webView, url;

- (void)viewDidLoad {
    [super viewDidLoad];
	NSURLRequest* urlRequest = [NSURLRequest requestWithURL:url];
	[webView loadRequest:urlRequest];
}

- (void)dealloc {
    [super dealloc];
	[webView release];
	[url release];
}

@end
