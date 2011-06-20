#import "WebViewController.h"


@implementation WebViewController

@synthesize webView, url;
- (void)webViewDidStartLoad:(UIWebView *)webView {
	UIApplication* application = [UIApplication sharedApplication];
	application.networkActivityIndicatorVisible = YES;
	imageView.hidden = NO;
	[imageView startAnimating];
}
- (void)webViewDidFinishLoad:(UIWebView *)webView {
	UIApplication* application = [UIApplication sharedApplication];
	application.networkActivityIndicatorVisible = NO;
	imageView.hidden = YES;
	[imageView stopAnimating];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	webView.delegate = self;
	[webView loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:url]]];
	imageView.animationImages = [NSArray arrayWithObjects:[UIImage imageNamed:@"term1anim.png"],
														  [UIImage imageNamed:@"term2anim.png"],
														  [UIImage imageNamed:@"term3anim.png"],
														  [UIImage imageNamed:@"term4anim.png"],
														  [UIImage imageNamed:@"term5anim.png"],
														  [UIImage imageNamed:@"term6anim.png"], nil];
	imageView.animationDuration = 1.2;
	imageView.animationRepeatCount = 0;
	imageView.hidden = YES;
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)viewDidUnload {
    [super viewDidUnload];
}
- (void)dealloc {
	[imageView release];
	[webView release];
    [super dealloc];
}

@end
