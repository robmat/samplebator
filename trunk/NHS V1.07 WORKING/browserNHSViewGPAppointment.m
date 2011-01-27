
#import "browserNHSViewGPAppointment.h"


@implementation browserNHSViewGPAppointment

@synthesize activity, TheWebView;


-(IBAction)GoHome:(id)sender{
	NSURL *url = [NSURL URLWithString:@"http://www.bbc.co.uk"];
	NSURLRequest *request = [NSURLRequest requestWithURL:url];
	[TheWebView loadRequest:request];
	
	[[self TheWebView] setDelegate:self] ;
	
    [super viewDidLoad];
}

-(IBAction)GoBack:(id)sender{
	[TheWebView goBack] ;
}

-(IBAction)GoForward:(id)sender{
	[TheWebView goForward] ;
}



-(void)webViewDidStartLoad:(UIWebView *)TheWebView{
	[activity startAnimating] ;
}

-(void)webViewDidFinishLoad:(UIWebView *)TheWebView{
	[activity stopAnimating] ;
}


// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.

- (void)viewDidLoad {

	activity.hidesWhenStopped = TRUE ;
	
	NSURL *url = [NSURL URLWithString:@"http://www.nhsdirect.nhs.uk"];
	NSURLRequest *request = [NSURLRequest requestWithURL:url];
	[TheWebView loadRequest:request];
	
	[[self TheWebView] setDelegate:self] ;
	
    [super viewDidLoad];
	
}



- (void)didReceiveMemoryWarning {
	// Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
	
	// Release any cached data, images, etc that aren't in use.
}

- (void)viewDidUnload {
	// Release any retained subviews of the main view.
	// e.g. self.myOutlet = nil;
}


- (void)dealloc {
	[activity release] ;
	[TheWebView release] ;
    [super dealloc];
}

-(void)viewWillDisappear:(BOOL)animated{
	
	//imagenTermo.hidden = TRUE ;
	
}

@end
