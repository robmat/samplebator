
//Copyright Applicable Ltd 2011

#import "PathwayCell.h"
#import "WebViewController.h"
#import "ItemDetailsViewController.h"

@implementation PathwayCell

@synthesize label, detailLabel, navController, background, data, detailsBtn, websiteBtn, phoneBtn;

- (void) initializeCell {
	if ([data objectForKey:@"website"] != nil && ![[data objectForKey:@"website"] isEqualToString:@""]) {
		websiteBtn.hidden = NO;
	} else {
		websiteBtn.hidden = YES;
	}
	if ([data objectForKey:@"phone"] != nil && ![[data objectForKey:@"phone"] isEqualToString:@""]) {
		phoneBtn.hidden = NO;
	} else {
		phoneBtn.hidden = YES;
	}
	if (!([data objectForKey:@"Text"] != nil && ![[data objectForKey:@"Text"] isEqualToString:@""])) {
		[self moveDownView:label byPixels:[NSNumber numberWithInt:11]];
	}
	NSNumber* viewType = [data objectForKey:@"View"];
	if (viewType == nil || [viewType isEqualToNumber:[NSNumber numberWithInt: 4]]) {
		detailsBtn.hidden = YES;
	} else {
		detailsBtn.hidden = NO;
	}
	label.text = [data objectForKey:@"Title"];
	detailLabel.text = [data objectForKey:@"Text"];
}
- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier {
    if ((self = [super initWithStyle:style reuseIdentifier:reuseIdentifier])) {

    }
    return self;
}
- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
    [super setSelected:selected animated:animated];
}
- (IBAction) urlAction: (id) sender {
	WebViewController* wvc = [[WebViewController alloc] initWithNibName:nil bundle:nil];
	NSString* urlStr = [data objectForKey:@"website"];
	if ([urlStr rangeOfString:@"http://"].location != 0) {
		urlStr = [NSString stringWithFormat:@"%@%@", @"http://", urlStr];
	}
	wvc.url = urlStr;
	wvc.title = label.text;
	[self.navController pushViewController:wvc animated:YES];
	[wvc release];
}
- (IBAction) phoneAction: (id) sender {
	NSMutableString* telNo = [NSMutableString stringWithFormat:@"%@%@", @"tel://", [data objectForKey:@"phone"]];
	while ([telNo rangeOfString:@" "].location != NSNotFound) {
		[telNo replaceCharactersInRange:[telNo rangeOfString:@" "] withString:@""];
	}
	NSURL *phoneNumberURL = [NSURL URLWithString:telNo];
	[[UIApplication sharedApplication] openURL:phoneNumberURL];
}
- (IBAction) detailsAction: (id) sender {
	ItemDetailsViewController* idvc = [[ItemDetailsViewController alloc] initWithNibName:nil bundle:nil];
	idvc.data = data;
	[self.navController pushViewController:idvc animated:YES];
	[idvc release];
}	
- (void) moveDownView: (UIView*) view byPixels: (NSNumber*) pixels {
	CGRect frame = view.frame;
	frame = CGRectMake(frame.origin.x, frame.origin.y + [pixels floatValue], frame.size.width, frame.size.height);
	view.frame = frame;
}
- (void)dealloc {
	[label release];
	[detailLabel release];
	[background release];
	[navController release];
	[data release];
	[websiteBtn release];
	[phoneBtn release];
	[detailsBtn release];
    [super dealloc];
}

@end
