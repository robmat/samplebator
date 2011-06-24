
//Copyright Applicable Ltd 2011

#import "ItemDetailsViewController.h"
#import <MessageUI/MessageUI.h>

@implementation ItemDetailsViewController

@synthesize data, titleLbl, addrLbl, addr2Lbl, cityLbl, postcodeLbl, phoneLbl, websiteLbl;

- (void)mailComposeController:(MFMailComposeViewController*)controller  
          didFinishWithResult:(MFMailComposeResult)result 
                        error:(NSError*)error {
	[self dismissModalViewControllerAnimated:YES];
}
- (IBAction) mailAction: (id) sender {
	MFMailComposeViewController* controller = [[MFMailComposeViewController alloc] init];
	controller.mailComposeDelegate = self;
	[controller setToRecipients:[NSArray arrayWithObjects:@"admin@smart-gp.co.uk", nil]];
	[self presentModalViewController:controller animated:YES];
	[controller release];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	titleLbl.text = [data objectForKey:@"Title"];
	addrLbl.text = [data objectForKey:@"address"];
	addr2Lbl.text = [data objectForKey:@"address2"];
	cityLbl.text = [data objectForKey:@"city"];
	phoneLbl.text = [data objectForKey:@"phone"];
	postcodeLbl.text = [data objectForKey:@"postcode"];
	websiteLbl.text = [data objectForKey:@"website"];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)viewDidUnload {
    [super viewDidUnload];
}
- (void)dealloc {
    [super dealloc];
	[data release];
	[titleLbl release];
	[addrLbl release];
	[addr2Lbl release];
	[cityLbl release];
	[postcodeLbl release];
	[phoneLbl release];
	[websiteLbl release];
}

@end
