#import "WebViewController.h"


@implementation WebViewController

@synthesize webView, url;
- (void)webViewDidStartLoad:(UIWebView *)webView {
	UIApplication* application = [UIApplication sharedApplication];
	application.networkActivityIndicatorVisible = YES;
}
- (void)webViewDidFinishLoad:(UIWebView *)webView {
	UIApplication* application = [UIApplication sharedApplication];
	application.networkActivityIndicatorVisible = NO;
}
- (void)viewDidLoad {
    [super viewDidLoad];
	webView.delegate = self;
	[webView loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:url]]];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)viewDidUnload {
    [super viewDidUnload];
}
- (void)dealloc {
	[webView release];
    [super dealloc];
}

@end
