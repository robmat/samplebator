
//Copyright Applicable Ltd 2011

#import "ContactViewController.h"
#import "WebViewController.h"
#import <MessageUI/MessageUI.h>
#import "DisclaimerPageViewController.h"

@implementation ContactViewController

- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = @"Contact";
}
- (void)mailComposeController:(MFMailComposeViewController*)controller  
          didFinishWithResult:(MFMailComposeResult)result 
                        error:(NSError*)error {
	[self dismissModalViewControllerAnimated:YES];
}
- (IBAction) mail1Action: (id) sender {
	MFMailComposeViewController* controller = [[MFMailComposeViewController alloc] init];
	controller.mailComposeDelegate = self;
	[controller setToRecipients:[NSArray arrayWithObjects:@"enquiries@healtheastcic.co.uk", nil]];
	[self presentModalViewController:controller animated:YES];
	[controller release];
}
- (IBAction) mail2Action: (id) sender {
	MFMailComposeViewController* controller = [[MFMailComposeViewController alloc] init];
	controller.mailComposeDelegate = self;
	[controller setToRecipients:[NSArray arrayWithObjects:@"admin@smart-gp.co.uk", nil]];
	[self presentModalViewController:controller animated:YES];
	[controller release];
}
- (IBAction) websiteAction: (id) sender {
	WebViewController* wvc = [[WebViewController alloc] initWithNibName:nil bundle:nil];
	wvc.url = @"http://www.smart-gp.co.uk/";
	[self.navigationController pushViewController:wvc animated:YES];
	[wvc release];
	
}
- (IBAction) disclaimerAction: (id) sender {
	DisclaimerPageViewController* cpvc = [[DisclaimerPageViewController alloc] initWithNibName:nil bundle:nil];
	[self.navigationController pushViewController:cpvc animated:YES];
	[cpvc release];
}
- (void)dealloc {
    [super dealloc];
}

@end
